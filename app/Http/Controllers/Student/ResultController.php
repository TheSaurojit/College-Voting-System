<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Models\Post;

class ResultController extends Controller
{
    /**
     * Display election results to students (only if published).
     */
    public function index()
    {
        $settings = ElectionSetting::current();

        $now = now();
        $isOngoing = $settings->voting_open && 
                     (!$settings->voting_start || $now->gte($settings->voting_start)) && 
                     (!$settings->voting_end || $now->lte($settings->voting_end));

        if ($isOngoing) {
            return view('student.results', [
                'posts'     => collect(),
                'published' => false,
                'ongoing'   => true,
            ]);
        }

        if (! $settings->results_published) {
            return view('student.results', [
                'posts'     => collect(),
                'published' => false,
                'ongoing'   => false,
            ]);
        }

        $posts = Post::with(['candidates' => function ($query) {
            $query->withCount('votes')
                ->orderByDesc('votes_count');
        }])
            ->withCount('votes')
            ->orderBy('display_order')
            ->get();

        return view('student.results', [
            'posts'     => $posts,
            'published' => true,
            'ongoing'   => false,
        ]);
    }
}
