<?php

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Admin user setup
    $this->admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // Create settings
    $this->settings = ElectionSetting::create([
        'title' => 'Tied Election Test',
        'voting_open' => false, // close voting so results can be published/viewed
        'results_published' => true,
    ]);

    // Create post
    $this->post = Post::create([
        'name' => 'Vice President',
        'description' => 'VP position',
        'display_order' => 1,
    ]);

    // Create candidates
    $this->candidate1 = Candidate::create([
        'name' => 'Candidate Alpha',
        'post_id' => $this->post->id,
    ]);

    $this->candidate2 = Candidate::create([
        'name' => 'Candidate Beta',
        'post_id' => $this->post->id,
    ]);

    // Create two students
    $this->student1 = Student::create([
        'name' => 'Voter 1',
        'phone' => '1111111111',
        'roll_no' => '101',
    ]);

    $this->student2 = Student::create([
        'name' => 'Voter 2',
        'phone' => '2222222222',
        'roll_no' => '102',
    ]);

    // Cast 1 vote to Candidate Alpha and 1 vote to Candidate Beta (TIE!)
    Vote::create([
        'student_id' => $this->student1->id,
        'post_id' => $this->post->id,
        'candidate_id' => $this->candidate1->id,
    ]);

    Vote::create([
        'student_id' => $this->student2->id,
        'post_id' => $this->post->id,
        'candidate_id' => $this->candidate2->id,
    ]);
});

test('admin results list displays Tied (1st) for both candidates', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.results.index'));

    $response->assertStatus(200);
    $response->assertSee('Tied (1st)');
});

test('admin results detail displays Tie (2 Candidates) status', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.results.post', $this->post));

    $response->assertStatus(200);
    $response->assertSee('Tie (2 Candidates)');
    $response->assertSee('Tied (1st)');
});

test('student results page displays Tie and Tied (1st)', function () {
    // Authenticate student session
    session(['student_id' => $this->student1->id]);

    $response = $this->get(route('student.results'));

    $response->assertStatus(200);
    $response->assertSee('Tie (2 Candidates)');
    $response->assertSee('Tied (1st)');
});
