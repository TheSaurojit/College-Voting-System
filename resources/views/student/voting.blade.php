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
        input[type="radio"]:checked + div .skip-circle {
            border-color: #d97706; /* Amber 600 */
            background-color: #d97706; /* Amber 600 */
        }
    </style>

    <div x-data="votingApp()" x-init="initApp()">
        {{-- Welcome Header --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Welcome, {{ $student->name }} 👋</h1>
            <p class="text-gray-500">Please make your selection for each position below. You can vote for a candidate or choose to skip.</p>
        </div>

        {{-- Posts/Voting Sections --}}
        <div class="space-y-8">
            @foreach($posts as $post)
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
                            <div>
                                <template x-if="selections[{{ $post->id }}] === null">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Pending Selection
                                    </span>
                                </template>
                                <template x-if="selections[{{ $post->id }}] !== null && selections[{{ $post->id }}] !== 'skip'">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                        Candidate Selected
                                    </span>
                                </template>
                                <template x-if="selections[{{ $post->id }}] === 'skip'">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold border border-gray-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        Position Skipped
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Candidates Grid --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($post->candidates as $candidate)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="post_{{ $post->id }}" value="{{ $candidate->id }}" 
                                        @change="selections[{{ $post->id }}] = '{{ $candidate->id }}'" 
                                        class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 transition-all duration-300 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-2 peer-checked:ring-indigo-200">
                                        <div class="flex items-center gap-4">
                                            {{-- Photo --}}
                                            <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
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
                                                <div class="w-7 h-7 rounded-full border-2 border-gray-300 tick-circle flex items-center justify-center transition-all">
                                                    <svg class="w-4 h-4 text-white opacity-0 tick-icon transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            {{-- Skip Option --}}
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="post_{{ $post->id }}" value="skip" 
                                    @change="selections[{{ $post->id }}] = 'skip'" 
                                    class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 hover:border-amber-300 hover:bg-amber-50/30 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:ring-2 peer-checked:ring-amber-200 transition-all duration-300">
                                    <div class="flex items-center gap-4">
                                        {{-- Icon representing abstain/skip --}}
                                        <div class="w-16 h-16 rounded-xl bg-amber-100/80 flex items-center justify-center text-amber-600 shrink-0 font-extrabold text-2xl">
                                            Ø
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-gray-900 truncate">Skip Position</h3>
                                            <p class="text-xs text-gray-400">Abstain from voting for {{ $post->name }}</p>
                                        </div>

                                        {{-- Check indicator --}}
                                        <div class="shrink-0">
                                            <div class="w-7 h-7 rounded-full border-2 border-gray-300 skip-circle flex items-center justify-center transition-all">
                                                <svg class="w-4 h-4 text-white opacity-0 tick-icon transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Submit Ballot Banner --}}
        <div class="mt-8 p-6 bg-white rounded-2xl shadow-sm border border-gray-200/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Submit Your Ballot</h3>
                <p class="text-sm text-gray-500 mt-0.5" x-show="!isBallotComplete()">You must make a selection (either choose a candidate or skip) for all positions above.</p>
                <p class="text-sm text-indigo-600 font-semibold mt-0.5" x-show="isBallotComplete()" x-cloak>Ready to submit! Review your selections, then click submit.</p>
            </div>
            <button type="button" @click="showConfirmModal = true" :disabled="!isBallotComplete()" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-base font-bold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Submit Final Ballot
            </button>
        </div>

        {{-- Beautiful Confirmation Modal --}}
        <div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            {{-- Backdrop --}}
            <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showConfirmModal = false"></div>

            {{-- Dialog --}}
            <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden z-10 border border-gray-100">
                <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 to-violet-600"></div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900">Cast Final Ballot?</h3>
                            <p class="mt-2 text-sm text-gray-500 leading-relaxed">Once submitted, your votes are final and cannot be modified. You will be locked out of the voting portal and will not be able to view the candidates again.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" @click="showConfirmModal = false" :disabled="submitting" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-150 focus:outline-none disabled:opacity-50">
                            Cancel
                        </button>
                        <button type="button" @click="submitBallot()" :disabled="submitting" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 rounded-xl shadow-lg shadow-indigo-200 transition-all duration-150 transform hover:-translate-y-0.5 focus:outline-none disabled:opacity-50 disabled:transform-none">
                            <span x-show="submitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span x-show="!submitting">Yes, Cast Ballot</span>
                            <span x-show="submitting">Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error Toast --}}
        <div x-show="showError" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-6 right-6 z-50 max-w-sm" x-cloak>
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-600 text-white shadow-2xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium" x-text="errorMessage"></p>
                <button @click="showError = false" class="ml-2 text-red-200 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function votingApp() {
        return {
            selections: {},
            postsList: @json($posts->pluck('id')),
            showConfirmModal: false,
            submitting: false,
            showError: false,
            errorMessage: '',

            initApp() {
                this.postsList.forEach(postId => {
                    this.selections[postId] = null;
                });
            },

            isBallotComplete() {
                return Object.keys(this.selections).length === this.postsList.length &&
                       Object.values(this.selections).every(val => val !== null);
            },

            async submitBallot() {
                if (!this.isBallotComplete()) return;

                this.submitting = true;

                try {
                    const response = await fetch('{{ route("student.cast-vote") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            votes: this.selections
                        }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.href = data.redirect || '{{ route("student.thank-you") }}';
                    } else {
                        this.showErrorToast(data.message || 'Failed to submit ballot. Please try again.');
                        this.submitting = false;
                        this.showConfirmModal = false;
                    }
                } catch (error) {
                    this.showErrorToast('Network error. Please check your connection and try again.');
                    this.submitting = false;
                    this.showConfirmModal = false;
                }
            },

            showErrorToast(message) {
                this.errorMessage = message;
                this.showError = true;
                setTimeout(() => { this.showError = false; }, 5000);
            }
        };
    }
</script>
@endsection
