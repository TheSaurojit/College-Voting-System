<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal') — College Voting System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50">
    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-gradient-to-r from-indigo-600 to-violet-600 shadow-lg shadow-indigo-500/20" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/20 backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">College Voting System</span>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    @if(session('student_id'))
                        <a href="{{ route('student.vote') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('student.vote') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            Vote
                        </a>
                        <a href="{{ route('student.results') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('student.results') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                            Results
                        </a>
                    @endif
                </div>

                {{-- Right Side --}}
                <div class="hidden md:flex items-center gap-4">
                    @if(session('student_id'))
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(session('student_name', 'S'), 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-indigo-100">{{ session('student_name', 'Student') }}</span>
                        </div>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-all duration-200 backdrop-blur-sm border border-white/20">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                    <svg x-show="!mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="md:hidden border-t border-white/10" x-cloak>
            <div class="px-4 py-3 space-y-1">
                @if(session('student_id'))
                    <a href="{{ route('student.vote') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10">Vote</a>
                    <a href="{{ route('student.results') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-indigo-100 hover:bg-white/10">Results</a>
                    <div class="border-t border-white/10 pt-2 mt-2">
                        <div class="flex items-center gap-2 px-3 py-2">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(session('student_name', 'S'), 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-white">{{ session('student_name', 'Student') }}</span>
                        </div>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-200 hover:bg-red-500/20">Logout</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mt-4">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mt-4">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 shadow-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
