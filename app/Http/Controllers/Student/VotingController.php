<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Post;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($student->voted) {
            return redirect()->route('student.thank-you')
                ->with('error', 'You have already casted your vote.');
        }

        $posts = Post::with(['candidates' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('display_order')
            ->get();

        return view('student.voting', compact('posts', 'student'));
    }

    /**
     * Cast a vote for a candidate (AJAX endpoint).
     */
    public function castVote(Request $request)
    {
        $student = Student::find(session('student_id'));

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.',
            ], 401);
        }

        if ($student->voted) {
            return response()->json([
                'success' => false,
                'message' => 'You have already casted your vote.',
            ], 403);
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
                'message' => 'Voting has not started yet.',
            ], 403);
        }

        if ($settings->voting_end && $now->gt($settings->voting_end)) {
            return response()->json([
                'success' => false,
                'message' => 'Voting has ended.',
            ], 403);
        }

        $request->validate([
            'votes' => ['required', 'array'],
        ]);

        $votesData = $request->input('votes');
        $posts = Post::all();

        foreach ($posts as $post) {
            if (!array_key_exists($post->id, $votesData)) {
                return response()->json([
                    'success' => false,
                    'message' => "Please select a candidate or choose to skip for the position: {$post->name}.",
                ], 422);
            }

            $choice = $votesData[$post->id];
            if ($choice !== 'skip') {
                $candidateExists = Candidate::where('id', $choice)
                    ->where('post_id', $post->id)
                    ->exists();
                if (!$candidateExists) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid candidate selected for position: {$post->name}.",
                    ], 422);
                }
            }
        }

        DB::transaction(function () use ($student, $votesData) {
            foreach ($votesData as $postId => $choice) {
                if ($choice !== 'skip') {
                    Vote::create([
                        'student_id'   => $student->id,
                        'post_id'      => $postId,
                        'candidate_id' => (int) $choice,
                    ]);
                }
            }
            $student->update(['voted' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Your ballot has been cast successfully!',
            'redirect'  => route('student.thank-you'),
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

        return view('student.thank-you', compact('student'));
    }
}
