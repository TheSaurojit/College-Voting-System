<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    /**
     * Display the voting page with all posts and candidates.
     */
    public function index()
    {
        $student = Student::find(session('student_id'));

        if (! $student) {
            return redirect()->route('student.login')
                ->withErrors(['phone' => 'Session expired. Please log in again.']);
        }

        $posts = Post::with(['candidates' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('display_order')
            ->get();

        // Attach voting status for each post
        $posts->each(function ($post) use ($student) {
            $post->has_voted    = $student->hasVotedFor($post->id);
            $post->voted_for_id = null;

            if ($post->has_voted) {
                $vote = Vote::where('student_id', $student->id)
                    ->where('post_id', $post->id)
                    ->first();

                $post->voted_for_id = $vote?->candidate_id;
            }
        });

        return view('student.voting', compact('posts', 'student'));
    }

    /**
     * Cast a vote for a candidate (AJAX endpoint).
     */
    public function castVote(Request $request)
    {
        $request->validate([
            'post_id'      => ['required', 'exists:posts,id'],
            'candidate_id' => ['required', 'exists:candidates,id'],
        ]);

        $student = Student::find(session('student_id'));

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.',
            ], 401);
        }

        // Verify candidate belongs to the specified post
        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('post_id', $request->post_id)
            ->first();

        if (! $candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid candidate for this post.',
            ], 422);
        }

        // Check if student has already voted for this post
        if ($student->hasVotedFor($request->post_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted for this post.',
            ], 409);
        }

        // Check if voting is open and within the time window
        $settings = ElectionSetting::current();

        if (! $settings->voting_open) {
            return response()->json([
                'success' => false,
                'message' => 'Voting is currently closed.',
            ], 403);
        }

        $now = now();

        if ($settings->voting_start && $now->lt($settings->voting_start)) {
            return response()->json([
                'success' => false,
                'message' => 'Voting has not started yet. It starts on ' . $settings->voting_start->format('Y-m-d H:i:s') . '.',
            ], 403);
        }

        if ($settings->voting_end && $now->gt($settings->voting_end)) {
            return response()->json([
                'success' => false,
                'message' => 'Voting has ended. It closed on ' . $settings->voting_end->format('Y-m-d H:i:s') . '.',
            ], 403);
        }

        // Create the vote record
        Vote::create([
            'student_id'   => $student->id,
            'post_id'      => $request->post_id,
            'candidate_id' => $request->candidate_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your vote has been cast successfully!',
        ]);
    }

    /**
     * Display the thank-you page with the student's voting summary.
     */
    public function thankYou()
    {
        $student = Student::find(session('student_id'));

        if (! $student) {
            return redirect()->route('student.login');
        }

        $votes = Vote::with(['post', 'candidate'])
            ->where('student_id', $student->id)
            ->get();

        return view('student.thank-you', compact('student', 'votes'));
    }
}
