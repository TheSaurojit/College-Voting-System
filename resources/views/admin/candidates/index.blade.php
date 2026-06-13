@extends('layouts.admin')

@section('title', 'Candidates')
@section('subtitle', 'Manage election candidates')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            {{-- Post Filter --}}
            <form method="GET" action="{{ route('admin.candidates.index') }}" class="flex items-center gap-2">
                <select name="post_id" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-white">
                    <option value="">All Posts</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ $selectedPost == $post->id ? 'selected' : '' }}>{{ $post->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <a href="{{ route('admin.candidates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-300 transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Candidate
        </a>
    </div>

    @if($candidates->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($candidates as $candidate)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group">
                    {{-- Photo --}}
                    <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                        @if($candidate->photo)
                            <img src="{{ asset($candidate->photo) }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                </div>
                            </div>
                        @endif
                        {{-- Post Badge --}}
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full bg-indigo-600/90 backdrop-blur-sm text-xs font-semibold text-white shadow-lg">
                                {{ $candidate->post->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-5">
                        <h3 class="text-base font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $candidate->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $candidate->semester ?? '' }}</p>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.candidates.edit', $candidate) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    data-confirm="This will permanently delete {{ $candidate->name }} and all associated votes. This action cannot be undone."
                                    data-confirm-title="Delete Candidate?"
                                    data-confirm-label="Yes, Delete"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($candidates->hasPages())
            <div class="mt-6">
                {{ $candidates->withQueryString()->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No candidates found</h3>
                <p class="text-sm text-gray-500 mb-6">{{ $selectedPost ? 'No candidates for this post yet.' : 'Get started by adding your first candidate.' }}</p>
                <a href="{{ route('admin.candidates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add Candidate
                </a>
            </div>
        </div>
    @endif
@endsection
