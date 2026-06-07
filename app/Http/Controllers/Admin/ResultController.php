<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Models\Post;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display election results for all posts.
     */
    public function index()
    {
        $posts = Post::with(['candidates' => function ($query) {
            $query->withCount('votes')->orderByDesc('votes_count');
        }, 'candidates'])
            ->withCount('votes')
            ->orderBy('display_order')
            ->get();

        $settings = ElectionSetting::current();

        return view('admin.results.index', compact('posts', 'settings'));
    }

    /**
     * Display detailed results for a specific post.
     */
    public function postDetail($id)
    {
        $post = Post::with(['candidates' => function ($query) {
            $query->withCount('votes')->orderByDesc('votes_count');
        }, 'candidates'])
            ->withCount('votes')
            ->findOrFail($id);

        $totalVotes = $post->votes_count;
        $winner     = $post->candidates->first();
        $settings   = ElectionSetting::current();

        return view('admin.results.post-detail', compact('post', 'totalVotes', 'winner', 'settings'));
    }

    /**
     * Publish election results.
     */
    public function publish(Request $request)
    {
        $settings = ElectionSetting::current();
        $settings->update(['results_published' => true]);

        return redirect()->back()->with('success', 'Results have been published successfully.');
    }

    /**
     * Unpublish election results.
     */
    public function unpublish(Request $request)
    {
        $settings = ElectionSetting::current();
        $settings->update(['results_published' => false]);

        return redirect()->back()->with('success', 'Results have been unpublished.');
    }
}
