@extends('layouts.student')

@section('title', 'Election Results')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Election Results</h1>
            <p class="text-gray-500">View the current standings for all election positions</p>
        </div>

        @if($ongoing ?? false)
            {{-- Elections are Ongoing --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Elections are Ongoing</h2>
                    <p class="text-gray-500 max-w-md mb-6">Results will be published after the election end time.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-medium border border-indigo-100">
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Voting is active
                    </div>
                </div>
            </div>
        @elseif(!($published ?? false))
            {{-- Results Not Published --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Results Coming Soon</h2>
                    <p class="text-gray-500 max-w-md mb-6">The election results will be announced soon. Please check back later once the results have been published by the administration.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-medium border border-indigo-100">
                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Awaiting publication
                    </div>
                </div>
            </div>
        @else
            {{-- Published Results --}}
            <div class="space-y-6">
                @forelse($posts as $post)
                    @php
                        $postCandidates = $post->candidates->sortByDesc('votes_count');
                        $maxVotes = $postCandidates->max('votes_count') ?: 0;
                        $totalPostVotes = $postCandidates->sum('votes_count');
                        $winners = $postCandidates->filter(function($c) use ($maxVotes) {
                            return $c->votes_count === $maxVotes && $maxVotes > 0;
                        });
                        $isTie = $winners->count() > 1;
                        $firstWinner = $winners->first();
                    @endphp

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden" x-data="{ expanded: true }">
                        {{-- Post Header --}}
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                            <div class="text-left">
                                <h2 class="text-lg font-bold text-gray-900">{{ $post->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $totalPostVotes }} total votes</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($isTie)
                                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Tie ({{ $winners->count() }} Candidates)
                                    </span>
                                @elseif($firstWinner)
                                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        {{ $firstWinner->name }}
                                    </span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>

                        {{-- Candidates --}}
                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-gray-100">
                            <div class="p-6 space-y-4">
                                @forelse($postCandidates as $index => $candidate)
                                    @php
                                        $percentage = $totalPostVotes > 0 ? round(($candidate->votes_count / $totalPostVotes) * 100, 1) : 0;
                                        $isWinner = $winners->contains($candidate->id);
                                    @endphp

                                    <div class="flex items-center gap-4 p-4 rounded-xl {{ $isWinner ? ($isTie ? 'bg-slate-50 border border-slate-200' : 'bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200') : 'bg-gray-50 border border-gray-100' }}">
                                        {{-- Rank --}}
                                        <div class="w-8 h-8 rounded-full {{ $isWinner ? ($isTie ? 'bg-slate-500 text-white' : 'bg-amber-500 text-white') : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-bold shrink-0">
                                            {{ $index + 1 }}
                                        </div>

                                        {{-- Photo --}}
                                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 {{ $isWinner ? ($isTie ? 'ring-2 ring-slate-400 ring-offset-1' : 'ring-2 ring-amber-400 ring-offset-1') : '' }}">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" class="w-full h-full object-cover" alt="{{ $candidate->name }}">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info + Bar --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-bold text-gray-900">{{ $candidate->name }}</span>
                                                    @if($isWinner)
                                                        @if($isTie)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                                                Tied (1st)
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                                Winner
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <span class="text-sm font-bold text-gray-900">{{ $candidate->votes_count }} <span class="text-xs text-gray-400 font-normal">({{ $percentage }}%)</span></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $isWinner ? ($isTie ? 'bg-gradient-to-r from-slate-400 to-slate-500' : 'bg-gradient-to-r from-amber-400 to-amber-500') : 'bg-gradient-to-r from-indigo-400 to-violet-500' }}" style="width: {{ $percentage }}%" x-data x-init="$el.style.width = '0%'; setTimeout(() => $el.style.width = '{{ $percentage }}%', 100)"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500 py-4">No candidates for this position.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 p-12 text-center">
                        <p class="text-gray-500">No election posts available.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
@endsection
