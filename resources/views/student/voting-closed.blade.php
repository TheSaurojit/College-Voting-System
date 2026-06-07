@extends('layouts.student')

@section('title', 'Voting Closed')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-slate-600 to-slate-700 px-6 py-12 text-center relative overflow-hidden">
                {{-- Decorative --}}
                <div class="absolute inset-0">
                    <div class="absolute top-6 left-10 w-20 h-20 border-2 border-white/10 rounded-full"></div>
                    <div class="absolute bottom-6 right-10 w-16 h-16 border-2 border-white/10 rounded-full"></div>
                    <div class="absolute top-10 right-1/4 w-10 h-10 border-2 border-white/10 rounded-full"></div>
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-5">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-white mb-2">Voting is Closed</h1>
                    <p class="text-slate-300 text-lg">The voting period is currently not active</p>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-8 text-center">
                <div class="max-w-md mx-auto">
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        The voting session is currently closed. Please wait for the administration to open the voting period.
                    </p>

                    {{-- Schedule Info --}}
                    @if(isset($settings) && ($settings->start_date || $settings->end_date))
                        <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Voting Schedule
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                                @if($settings->start_date)
                                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Opens</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($settings->start_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($settings->start_date)->format('h:i A') }}</p>
                                    </div>
                                @endif
                                @if($settings->end_date)
                                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Closes</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($settings->end_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($settings->end_date)->format('h:i A') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('student.results') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            View Results
                        </a>
                        <form method="POST" action="{{ route('student.logout') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors border border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
