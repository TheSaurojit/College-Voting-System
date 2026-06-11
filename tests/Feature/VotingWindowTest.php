<?php

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Clean up settings to ensure we start fresh
    ElectionSetting::truncate();

    // Create default settings
    $this->settings = ElectionSetting::create([
        'title' => 'Students Union Election',
        'voting_open' => true,
        'results_published' => false,
        'voting_start' => now()->subHour(),
        'voting_end' => now()->addHour(),
    ]);

    // Create a student, post, and candidate
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

    // Set student session
    session(['student_id' => $this->student->id]);
});

test('a student can cast a ballot with votes when voting is open', function () {
    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => $this->candidate->id,
        ],
    ]);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'message' => 'Your ballot has been cast successfully!',
    ]);

    $this->assertDatabaseHas('votes', [
        'student_id' => $this->student->id,
        'post_id' => $this->post->id,
        'candidate_id' => $this->candidate->id,
    ]);

    $this->student->refresh();
    $this->assertTrue((bool)$this->student->voted);
});

test('a student can cast a ballot and skip positions', function () {
    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => 'skip',
        ],
    ]);

    $response->assertOk();
    
    // No vote should be saved since it was skipped
    $this->assertDatabaseEmpty('votes');

    $this->student->refresh();
    $this->assertTrue((bool)$this->student->voted);
});

test('a student cannot cast a vote when voting is disabled', function () {
    $this->settings->update(['voting_open' => false]);

    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => $this->candidate->id,
        ],
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Voting is currently closed.',
    ]);

    $this->assertDatabaseEmpty('votes');
});

test('a student cannot cast a vote when voting has not started yet', function () {
    $this->settings->update([
        'voting_start' => now()->addHour(),
        'voting_end' => now()->addHours(2),
    ]);

    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => $this->candidate->id,
        ],
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Voting has not started yet.',
    ]);

    $this->assertDatabaseEmpty('votes');
});

test('a student cannot cast a vote when voting has already ended', function () {
    $this->settings->update([
        'voting_start' => now()->subHours(2),
        'voting_end' => now()->subHour(),
    ]);

    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => $this->candidate->id,
        ],
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'Voting has already ended.',
    ]);

    $this->assertDatabaseEmpty('votes');
});

test('a student who has voted is redirected from vote page', function () {
    $this->student->update(['voted' => true]);

    $response = $this->get(route('student.vote'));

    $response->assertRedirect(route('student.thank-you'));
});

test('a student who has voted cannot vote again', function () {
    $this->student->update(['voted' => true]);

    $response = $this->postJson(route('student.cast-vote'), [
        'votes' => [
            $this->post->id => $this->candidate->id,
        ],
    ]);

    $response->assertStatus(403);
    $response->assertJson([
        'success' => false,
        'message' => 'You have already casted your vote.',
    ]);
});

test('voting page redirects to results if voting is closed', function () {
    $this->settings->update(['voting_open' => false]);

    $response = $this->get(route('student.vote'));

    $response->assertRedirect(route('student.results'));
    $response->assertSessionHas('error', 'Voting is currently closed.');
});

test('voting page redirects to results if voting has not started yet', function () {
    $this->settings->update([
        'voting_start' => now()->addHour(),
        'voting_end' => now()->addHours(2),
    ]);

    $response = $this->get(route('student.vote'));

    $response->assertRedirect(route('student.results'));
    $response->assertSessionHas('error', 'Voting has not started yet.');
});

test('voting page redirects to results if voting has already ended', function () {
    $this->settings->update([
        'voting_start' => now()->subHours(2),
        'voting_end' => now()->subHour(),
    ]);

    $response = $this->get(route('student.vote'));

    $response->assertRedirect(route('student.results'));
    $response->assertSessionHas('error', 'Voting has already ended.');
});
