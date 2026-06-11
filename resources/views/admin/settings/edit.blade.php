@extends('layouts.admin')

@section('title', 'Election Settings')
@section('subtitle', 'Configure the election system')

@section('content')
    <div class="max-w-3xl space-y-6">

        {{-- ── Voting Status Toggle Card ────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Voting Status</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Open or close voting for all students.</p>
                </div>

                {{-- Live status badge --}}
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold
                    {{ $settings->voting_open ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                    <span
                        class="w-2 h-2 rounded-full inline-block
                        {{ $settings->voting_open ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $settings->voting_open ? 'Voting Open' : 'Voting Closed' }}
                </span>
            </div>

            <div class="px-6 py-5">
                <form method="POST" action="{{ route('admin.settings.toggle-voting') }}">
                    @csrf

                    <div
                        class="flex items-start gap-4 p-4 rounded-xl
                        {{ $settings->voting_open ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">

                        <div class="flex-shrink-0 mt-0.5">
                            @if ($settings->voting_open)
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <p
                                class="text-sm font-semibold {{ $settings->voting_open ? 'text-emerald-800' : 'text-red-700' }}">
                                {{ $settings->voting_open
                                    ? 'Students can currently cast their votes.'
                                    : 'Voting is currently disabled. Students cannot vote.' }}
                            </p>
                            <p class="text-xs mt-1 {{ $settings->voting_open ? 'text-emerald-600' : 'text-red-500' }}">
                                Click the button below to {{ $settings->voting_open ? 'close' : 'open' }} voting.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            data-confirm="{{ $settings->voting_open ? 'Students will no longer be able to cast votes.' : 'Students will be able to start casting their votes.' }}"
                            data-confirm-title="{{ $settings->voting_open ? 'Close Voting?' : 'Open Voting?' }}"
                            data-confirm-label="{{ $settings->voting_open ? 'Yes, Close' : 'Yes, Open' }}"
                            data-confirm-type="{{ $settings->voting_open ? 'danger' : 'info' }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg transform hover:-translate-y-0.5
                            {{ $settings->voting_open
                                ? 'bg-gradient-to-r from-red-500 to-rose-600 text-white hover:from-red-600 hover:to-rose-700 shadow-red-200'
                                : 'bg-gradient-to-r from-emerald-500 to-green-600 text-white hover:from-emerald-600 hover:to-green-700 shadow-emerald-200' }}">
                            @if ($settings->voting_open)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Close Voting
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Open Voting
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── General Settings Form ────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">General Settings</h3>
                <p class="text-sm text-gray-500 mt-0.5">Configure the election title and schedule.</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Election Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Election Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $settings->title) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200"
                        placeholder="e.g. Students Union Election 2025">
                    @error('title')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Voting Window --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="voting_start" class="block text-sm font-semibold text-gray-700 mb-2">
                            Voting Start <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="voting_start" name="voting_start"
                            value="{{ old('voting_start', $settings->voting_start?->format('Y-m-d\TH:i')) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                        @error('voting_start')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="voting_end" class="block text-sm font-semibold text-gray-700 mb-2">
                            Voting End <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="voting_end" name="voting_end"
                            value="{{ old('voting_end', $settings->voting_end?->format('Y-m-d\TH:i')) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                        @error('voting_end')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Current Schedule Info --}}
                @if ($settings->voting_start || $settings->voting_end)
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="text-sm text-indigo-700">
                            @if ($settings->voting_start)
                                <span class="font-semibold">Start:</span>
                                {{ $settings->voting_start->format('d M Y, h:i A') }}
                            @endif
                            @if ($settings->voting_start && $settings->voting_end)
                                &nbsp;→&nbsp;
                            @endif
                            @if ($settings->voting_end)
                                <span class="font-semibold">End:</span>
                                {{ $settings->voting_end->format('d M Y, h:i A') }}
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Voting Open Toggle (checkbox) --}}
                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <input type="checkbox" id="voting_open" name="voting_open" value="1"
                        {{ $settings->voting_open ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="voting_open" class="text-sm font-semibold text-gray-700 cursor-pointer">
                        Voting is open
                        <span class="block text-xs font-normal text-gray-400 mt-0.5">
                            Check this to allow students to cast votes.
                        </span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white
                               text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200
                               transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Danger Zone ──────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-red-100">
                <h3 class="text-lg font-bold text-red-600">Danger Zone</h3>
                <p class="text-sm text-gray-500 mt-0.5">Irreversible actions — please be careful.</p>
            </div>

            <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Publish Results</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Make results visible to all students. Currently:
                        <span
                            class="font-semibold {{ $settings->results_published ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $settings->results_published ? 'Published' : 'Hidden' }}
                        </span>
                    </p>
                </div>

                @if ($settings->results_published)
                    <form method="POST" action="{{ route('admin.results.unpublish') }}">
                    @else
                        <form method="POST" action="{{ route('admin.results.publish') }}">
                @endif

                @csrf
                <button type="submit"
                    data-confirm="{{ $settings->results_published ? 'Results will be hidden from students.' : 'Results will become visible to all students immediately.' }}"
                    data-confirm-title="{{ $settings->results_published ? 'Unpublish Results?' : 'Publish Results?' }}"
                    data-confirm-label="{{ $settings->results_published ? 'Yes, Unpublish' : 'Yes, Publish' }}"
                    data-confirm-type="{{ $settings->results_published ? 'danger' : 'info' }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200
                            {{ $settings->results_published
                                ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                : 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' }}">
                    @if ($settings->results_published)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                        Unpublish Results
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Publish Results
                    @endif
                </button>
                </form>
            </div>
        </div>

    </div>
@endsection
