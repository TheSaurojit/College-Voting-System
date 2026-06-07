<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Display a listing of candidates with optional post filter.
     */
    public function index(Request $request)
    {
        $query = Candidate::with(['post']);

        if ($request->filled('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        $candidates  = $query->orderBy('name')->paginate(15)->withQueryString();
        $posts       = Post::orderBy('display_order')->get();
        $selectedPost = request('post_id');

        return view('admin.candidates.index', compact('candidates', 'posts', 'selectedPost'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        $posts = Post::orderBy('display_order')->get();

        return view('admin.candidates.create', compact('posts'));
    }

    /**
     * Store a newly created candidate.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'post_id'  => ['required', 'exists:posts,id'],
            'semester' => ['nullable', 'string', 'max:20'],
            'photo'    => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'post_id', 'semester']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate created successfully.');
    }

    /**
     * Display the specified candidate.
     */
    public function show(Candidate $candidate)
    {
        $candidate->load(['post']);
        $candidate->loadCount('votes');

        return view('admin.candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate)
    {
        $posts = Post::orderBy('display_order')->get();

        return view('admin.candidates.edit', compact('candidate', 'posts'));
    }

    /**
     * Update the specified candidate.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'post_id'  => ['required', 'exists:posts,id'],
            'semester' => ['nullable', 'string', 'max:20'],
            'photo'    => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'post_id', 'semester']);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }

            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified candidate, their votes, and photo.
     */
    public function destroy(Candidate $candidate)
    {
        // Delete related votes
        $candidate->votes()->delete();

        // Delete photo from storage
        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }
}
