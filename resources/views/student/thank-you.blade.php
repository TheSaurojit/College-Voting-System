@extends('layouts.student')

@section('title', 'Thank You')

@section('content')
    <div class="max-w-2xl mx-auto">
        {{-- Decorative Elements --}}
        <div class="relative">
            {{-- Confetti-like decorations --}}
            <div class="absolute -top-4 left-1/4 w-3 h-3 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0s; animation-duration: 2s;"></div>
            <div class="absolute -top-2 left-1/2 w-2 h-2 bg-violet-400 rounded-full animate-bounce" style="animation-delay: 0.3s; animation-duration: 2.5s;"></div>
            <div class="absolute -top-6 right-1/4 w-4 h-4 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.6s; animation-duration: 1.8s;"></div>
            <div class="absolute -top-3 left-1/6 w-2 h-2 bg-amber-400 rounded-full animate-bounce" style="animation-delay: 0.2s; animation-duration: 2.2s;"></div>
            <div class="absolute -top-5 right-1/6 w-3 h-3 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.5s; animation-duration: 2.1s;"></div>
            <div class="absolute -top-2 right-1/3 w-2 h-2 bg-sky-400 rounded-full animate-bounce" style="animation-delay: 0.8s; animation-duration: 1.9s;"></div>

            {{-- Main Card --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                {{-- Success Header --}}
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-4 left-8 w-16 h-16 border-4 border-white rounded-full"></div>
                        <div class="absolute bottom-4 right-12 w-12 h-12 border-4 border-white rounded-full"></div>
                        <div class="absolute top-8 right-1/4 w-8 h-8 border-4 border-white rounded-full"></div>
                    </div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h1 class="text-3xl font-extrabold text-white mb-2">Thank You for Voting!</h1>
                        <p class="text-emerald-100 text-lg">Your voice has been heard, {{ $student->name }} 🎉</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="p-8 text-center bg-gray-50/50 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-6">You have completed the voting process. You can view the results if published or log out safely.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('student.results') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 shadow-lg shadow-indigo-200 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            View Results
                        </a>
                        <form method="POST" action="{{ route('student.logout') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors border border-gray-200 transform hover:-translate-y-0.5 shadow-sm hover:shadow">
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
