@extends('layouts.admin')

@section('title', 'Election Results')
@section('subtitle', 'View and manage election results')

@section('content')
    {{-- Header with Publish/Unpublish --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            @if($settings->results_published ?? false)
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-semibold border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Results Published
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-sm font-semibold border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Results Not Published
                </span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($settings->results_published ?? false)
                <form method="POST" action="{{ route('admin.results.unpublish') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 text-white text-sm font-semibold rounded-xl hover:bg-amber-700 transition-colors shadow-lg shadow-amber-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Unpublish Results
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.results.publish') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Publish Results
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="space-y-6">
            @foreach($posts as $post)
                @php
                    $postCandidates = $post->candidates->sortByDesc('votes_count');
                    $maxVotes = $postCandidates->max('votes_count') ?: 1;
                    $totalPostVotes = $postCandidates->sum('votes_count');
                    $winner = $postCandidates->first();
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $post->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $totalPostVotes }} total votes</p>
                        </div>
                        <a href="{{ route('admin.results.post', $post) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                            View Details
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse($postCandidates as $index => $candidate)
                            @php
                                $percentage = $totalPostVotes > 0 ? round(($candidate->votes_count / $totalPostVotes) * 100, 1) : 0;
                                $isWinner = $index === 0 && $candidate->votes_count > 0;
                            @endphp

                            <div class="flex items-center gap-4 {{ $isWinner ? 'bg-amber-50 -mx-2 px-2 py-3 rounded-xl border border-amber-100' : '' }}">
                                {{-- Photo/Avatar --}}
                                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 {{ $isWinner ? 'ring-2 ring-amber-400 ring-offset-2' : '' }}">
                                    @if($candidate->photo)
                                        <img src="{{ asset('storage/' . $candidate->photo) }}" class="w-full h-full object-cover" alt="{{ $candidate->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Info + Bar --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $candidate->name }}</span>
                                            @if($isWinner)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                    Winner
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-bold text-gray-900">{{ $candidate->votes_count }}</span>
                                            <span class="text-xs text-gray-400 ml-1">({{ $percentage }}%)</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $isWinner ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-indigo-400 to-violet-500' }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No candidates for this post.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No results to display</h3>
                <p class="text-sm text-gray-500">Create posts and candidates first to see results here.</p>
            </div>
        </div>
    @endif
@endsection
