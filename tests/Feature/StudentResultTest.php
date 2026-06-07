<?php

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    ElectionSetting::truncate();

    $this->settings = ElectionSetting::create([
        'title' => 'Students Union Election',
        'voting_open' => true,
        'results_published' => false,
        'voting_start' => now()->subHour(),
        'voting_end' => now()->addHour(),
    ]);

    $this->student = Student::create([
        'name' => 'John Doe',
        'phone' => '1234567890',
        'roll_no' => '101',
        'class' => '11',
        'semester' => '1',
    ]);

    $this->post = Post::create([
        'name' => 'President',
        'description' => 'President of the Union',
        'display_order' => 1,
    ]);

    $this->candidate = Candidate::create([
        'name' => 'Alice Smith',
        'post_id' => $this->post->id,
        'semester' => '3',
    ]);

    session(['student_id' => $this->student->id]);
});

test('students see ongoing message if election is active', function () {
    $response = $this->get(route('student.results'));

    $response->assertOk();
    $response->assertSee('Elections are Ongoing');
    $response->assertSee('Results will be published after the election end time.');
    $response->assertDontSee('Alice Smith');
});

test('students see results coming soon message if election is not active and not published', function () {
    // End the voting window
    $this->settings->update([
        'voting_open' => false,
        'results_published' => false,
    ]);

    $response = $this->get(route('student.results'));

    $response->assertOk();
    $response->assertSee('Results Coming Soon');
    $response->assertDontSee('Elections are Ongoing');
    $response->assertDontSee('Alice Smith');
});

test('students see actual results if election is not active and results are published', function () {
    // End the voting window and publish results
    $this->settings->update([
        'voting_open' => false,
        'results_published' => true,
    ]);

    $response = $this->get(route('student.results'));

    $response->assertOk();
    $response->assertSee('Alice Smith');
    $response->assertDontSee('Elections are Ongoing');
    $response->assertDontSee('Results Coming Soon');
});
