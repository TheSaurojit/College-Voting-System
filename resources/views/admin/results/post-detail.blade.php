@extends('layouts.admin')

@section('title', $post->name . ' — Results')
@section('subtitle', 'Detailed results for this post')

@section('content')
    {{-- Back Link --}}
    <a href="{{ route('admin.results.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to All Results
    </a>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Post</p>
            <p class="text-xl font-bold text-gray-900">{{ $post->name }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Votes</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($totalVotes) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Winner</p>
            <div class="flex items-center gap-2">
                @if($winner)
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <p class="text-xl font-bold text-gray-900">{{ $winner->name }}</p>
                @else
                    <p class="text-xl font-bold text-gray-400">No votes yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Candidates Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Candidates</h3>
        </div>

        <div class="p-6 space-y-6">
            @php
                $candidates = $post->candidates->sortByDesc('votes_count');
            @endphp

            @forelse($candidates as $index => $candidate)
                @php
                    $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                    $isWinner = $winner && $candidate->id === $winner->id && $candidate->votes_count > 0;
                @endphp

                <div class="p-5 rounded-2xl {{ $isWinner ? 'bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-200' : 'bg-gray-50 border border-gray-100' }}">
                    <div class="flex items-start gap-4">
                        {{-- Rank --}}
                        <div class="w-8 h-8 rounded-full {{ $isWinner ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-bold shrink-0">
                            {{ $index + 1 }}
                        </div>

                        {{-- Photo --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 {{ $isWinner ? 'ring-2 ring-amber-400 ring-offset-2' : '' }}">
                            @if($candidate->photo)
                                <img src="{{ asset('storage/' . $candidate->photo) }}" class="w-full h-full object-cover" alt="{{ $candidate->name }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-lg font-bold">
                                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-base font-bold text-gray-900">{{ $candidate->name }}</h4>
                                @if($isWinner)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Winner
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mb-3">{{ $candidate->semester ? $candidate->semester : 'No class/semester' }}</p>

                            {{-- Vote Bar --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $isWinner ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-indigo-400 to-violet-500' }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 w-28">
                                    <span class="text-lg font-bold text-gray-900">{{ $candidate->votes_count }}</span>
                                    <span class="text-sm text-gray-400 ml-1">({{ $percentage }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No candidates found for this post.</p>
            @endforelse
        </div>
    </div>
@endsection
