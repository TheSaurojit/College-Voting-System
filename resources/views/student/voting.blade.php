@extends('layouts.student')

@section('title', 'Cast Your Vote')

@section('content')
    <style>
        /* Show tick mark and fill circle box when candidate card is selected */
        input[type="radio"]:checked + div .tick-circle {
            border-color: #4f46e5; /* Indigo 600 */
            background-color: #4f46e5; /* Indigo 600 */
        }
        input[type="radio"]:checked + div .tick-icon {
            opacity: 1 !important;
        }
        
        /* Emerald theme for the circle box after the vote is cast */
        .voted-tick {
            border-color: #10b981 !important; /* Emerald 500 */
            background-color: #10b981 !important; /* Emerald 500 */
        }
    </style>

    <div x-data="votingApp()">
        {{-- Welcome Header --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Welcome, {{ $student->name }} 👋</h1>
            <p class="text-gray-500">Cast your vote for each position below. Your vote is confidential.</p>
        </div>

        {{-- Posts/Voting Sections --}}
        <div class="space-y-8">
            @foreach($posts as $postIndex => $post)
                @php
                    $hasVoted = $post->has_voted;
                    $votedCandidateId = $post->voted_for_id;
                @endphp

                <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden" id="post-{{ $post->id }}">
                    {{-- Post Header --}}
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50/50 to-violet-50/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $post->name }}</h2>
                                @if($post->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $post->description }}</p>
                                @endif
                            </div>
                            <div id="status-{{ $post->id }}">
                                @if($hasVoted)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Vote Cast ✓
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Candidates Grid --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="candidates-{{ $post->id }}">
                            @foreach($post->candidates as $candidate)
                                @php
                                    $isVotedCandidate = $votedCandidateId === $candidate->id;
                                @endphp

                                <label class="relative cursor-pointer group {{ $hasVoted ? 'pointer-events-none' : '' }}" id="label-candidate-{{ $candidate->id }}">
                                    <input type="radio" name="post_{{ $post->id }}" value="{{ $candidate->id }}" class="peer sr-only" {{ $hasVoted ? 'disabled' : '' }} {{ $isVotedCandidate ? 'checked' : '' }}>
                                    <div class="p-4 rounded-xl border-2 transition-all duration-300 {{ $isVotedCandidate ? 'border-emerald-400 bg-emerald-50 ring-2 ring-emerald-200' : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-2 peer-checked:ring-indigo-200' }}">
                                        <div class="flex items-center gap-4">
                                            {{-- Photo --}}
                                            <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 {{ $isVotedCandidate ? 'ring-2 ring-emerald-400' : '' }}">
                                                @if($candidate->photo)
                                                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-xl font-bold">
                                                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-bold text-gray-900 truncate">{{ $candidate->name }}</h3>
                                                @if($candidate->semester)
                                                    <p class="text-xs text-gray-400">{{ $candidate->semester }}</p>
                                                @endif
                                            </div>

                                            {{-- Check indicator --}}
                                            <div class="shrink-0">
                                                @if($isVotedCandidate)
                                                    <div class="w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                @else
                                                    <div class="w-7 h-7 rounded-full border-2 border-gray-300 tick-circle flex items-center justify-center transition-all">
                                                        <svg class="w-4 h-4 text-white opacity-0 tick-icon transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- Vote Button --}}
                        @unless($hasVoted)
                            <div class="mt-5 flex justify-end" id="btn-container-{{ $post->id }}">
                                <button type="button" @click="castVote({{ $post->id }})" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" id="vote-btn-{{ $post->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Cast Vote
                                </button>
                            </div>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>

        {{-- All Voted Banner --}}
        @php
            $allVoted = $posts->every(function($post) {
                return $post->candidates->contains(function($candidate) {
                    return $candidate->pivot_voted ?? $candidate->voted ?? false;
                });
            });
        @endphp

        @if($allVoted && $posts->count() > 0)
            <div class="mt-8 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-emerald-900 mb-1">All Votes Cast!</h3>
                <p class="text-sm text-emerald-700 mb-4">You have successfully voted for all positions.</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('student.thank-you') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                        View Summary
                    </a>
                    <a href="{{ route('student.results') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-700 text-sm font-semibold rounded-xl hover:bg-emerald-50 transition-colors border border-emerald-200">
                        View Results
                    </a>
                </div>
            </div>
        @endif

        {{-- Error Toast --}}
        <div x-show="showError" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-6 right-6 z-50 max-w-sm" x-cloak>
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-600 text-white shadow-2xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium" x-text="errorMessage"></p>
                <button @click="showError = false" class="ml-2 text-red-200 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        </div>

        {{-- Success Toast --}}
        <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-6 right-6 z-50 max-w-sm" x-cloak>
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-600 text-white shadow-2xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium" x-text="successMessage"></p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function votingApp() {
        return {
            showError: false,
            errorMessage: '',
            showSuccess: false,
            successMessage: '',

            async castVote(postId) {
                const selectedRadio = document.querySelector(`input[name="post_${postId}"]:checked`);
                if (!selectedRadio) {
                    this.showErrorToast('Please select a candidate before voting.');
                    return;
                }

                const candidateId = selectedRadio.value;
                const btn = document.getElementById(`vote-btn-${postId}`);
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Casting Vote...';

                try {
                    const response = await fetch('{{ route("student.cast-vote") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            candidate_id: candidateId,
                        }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Update status badge
                        const statusEl = document.getElementById(`status-${postId}`);
                        statusEl.innerHTML = '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Vote Cast ✓</span>';

                        // Disable all radios for this post
                        document.querySelectorAll(`input[name="post_${postId}"]`).forEach(r => { r.disabled = true; });

                        // Disable labels
                        document.querySelectorAll(`#candidates-${postId} label`).forEach(l => { l.classList.add('pointer-events-none'); });

                        // Highlight selected candidate
                        const selectedLabel = document.getElementById(`label-candidate-${candidateId}`);
                        if (selectedLabel) {
                            const card = selectedLabel.querySelector('div');
                            card.classList.remove('border-gray-200', 'peer-checked:border-indigo-500', 'peer-checked:bg-indigo-50', 'peer-checked:ring-2', 'peer-checked:ring-indigo-200');
                            card.classList.add('border-emerald-400', 'bg-emerald-50', 'ring-2', 'ring-emerald-200');

                            // Color the tick circle emerald after successful vote
                            const tickCircle = card.querySelector('.tick-circle');
                            if (tickCircle) {
                                tickCircle.classList.remove('border-gray-300');
                                tickCircle.classList.add('voted-tick');
                            }
                        }

                        // Remove vote button
                        const btnContainer = document.getElementById(`btn-container-${postId}`);
                        if (btnContainer) btnContainer.remove();

                        this.showSuccessToast(data.message || 'Vote cast successfully!');
                    } else {
                        this.showErrorToast(data.message || 'Failed to cast vote. Please try again.');
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Cast Vote';
                    }
                } catch (error) {
                    this.showErrorToast('Network error. Please check your connection and try again.');
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Cast Vote';
                }
            },

            showErrorToast(message) {
                this.errorMessage = message;
                this.showError = true;
                setTimeout(() => { this.showError = false; }, 5000);
            },

            showSuccessToast(message) {
                this.successMessage = message;
                this.showSuccess = true;
                setTimeout(() => { this.showSuccess = false; }, 4000);
            },
        };
    }
</script>
@endsection
