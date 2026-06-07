@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your election system')

@section('content')
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Students --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Students</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalStudents) }}</p>
            <p class="text-sm text-gray-500 mt-1">Registered students</p>
        </div>

        {{-- Total Posts --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Posts</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalPosts) }}</p>
            <p class="text-sm text-gray-500 mt-1">Election posts</p>
        </div>

        {{-- Total Candidates --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Candidates</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalCandidates) }}</p>
            <p class="text-sm text-gray-500 mt-1">Active candidates</p>
        </div>

        {{-- Total Votes --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Votes</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalVotes) }}</p>
            <p class="text-sm text-gray-500 mt-1">Votes cast</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Election Status Card --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Election Status</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ $settings->title ?? 'College Election' }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Current election cycle</p>
                    </div>
                    <div>
                        @php
                            $now = now();
                            $status = 'closed';
                            if ($settings->voting_open) {
                                if ($settings->voting_start && $now->lt($settings->voting_start)) {
                                    $status = 'scheduled';
                                } elseif ($settings->voting_end && $now->gt($settings->voting_end)) {
                                    $status = 'ended';
                                } else {
                                    $status = 'open';
                                }
                            }
                        @endphp

                        @if($status === 'open')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 text-sm font-semibold border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Voting Open
                            </span>
                        @elseif($status === 'scheduled')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold border border-blue-200">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Scheduled
                            </span>
                        @elseif($status === 'ended')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 text-amber-700 text-sm font-semibold border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Voting Ended
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-red-700 text-sm font-semibold border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Voting Closed
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Start Date</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $settings->voting_start ? \Carbon\Carbon::parse($settings->voting_start )->format('M d, Y h:i A') : 'Not set' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">End Date</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $settings->voting_end ? \Carbon\Carbon::parse($settings->voting_end )->format('M d, Y h:i A') : 'Not set' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-3">
                <form method="POST" action="{{ route('admin.settings.toggle-voting') }}">
                    @csrf
                    @if($settings->voting_open)
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 transition-colors duration-200 group">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold">Close Voting</p>
                                <p class="text-xs text-red-500">Stop accepting votes</p>
                            </div>
                        </button>
                    @else
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors duration-200 group">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold">Open Voting</p>
                                <p class="text-xs text-emerald-500">Start accepting votes</p>
                            </div>
                        </button>
                    @endif
                </form>

                <a href="{{ route('admin.results.index') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors duration-200 group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold">View Results</p>
                        <p class="text-xs text-indigo-500">See election results</p>
                    </div>
                </a>

                <a href="{{ route('admin.candidates.index') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors duration-200 group">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold">Manage Candidates</p>
                        <p class="text-xs text-amber-500">Add or edit candidates</p>
                    </div>
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors duration-200 group">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold">Settings</p>
                        <p class="text-xs text-slate-500">Configure election</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
