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

        if (! $settings->results_published) {
            return view('student.results', [
                'posts'     => collect(),
                'published' => false,
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
        ]);
    }
}
