<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts ordered by display order.
     */
    public function index()
    {
        $posts = Post::withCount('candidates')
            ->orderBy('display_order')
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        Post::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'display_order' => $request->display_order ?? 0,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post->loadCount('candidates');
        $post->load('candidates');

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $post->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'display_order' => $request->display_order ?? 0,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post (cascades to candidates and votes).
     */
    public function destroy(Post $post)
    {
        // Delete related votes for all candidates of this post
        $post->votes()->delete();

        // Delete related candidates
        $post->candidates()->delete();

        // Delete the post
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post and all related candidates and votes deleted successfully.');
    }
}
