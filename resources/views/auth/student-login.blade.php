<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login — College Voting System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 bg-no-repeat bg-fixed relative overflow-x-hidden overflow-y-auto">
    {{-- Background Decorations --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-pink-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 left-1/3 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{-- Branding --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-4 shadow-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-1">Student Login</h1>
                <p class="text-indigo-200">College Voting System</p>
            </div>

            {{-- Glassmorphism Card --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-2xl p-8" x-data="{
                programType: '{{ old('program_type', '') }}',
                init() {
                    this.$watch('programType', (val) => {
                        if (val === '+2') {
                            const sem = document.getElementById('semester');
                            if (sem) sem.value = '';
                        } else if (val === 'Degree') {
                            const cls = document.getElementById('class');
                            if (cls) cls.value = '';
                        }
                    });
                }
            }">
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-400/30">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-semibold text-red-200">Please fix the following:</p>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-200 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.send-otp') }}" class="space-y-5">
                    @csrf

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-white/90 mb-2">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200" placeholder="Enter your phone number">
                        </div>
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Program Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-3">Program Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="program_type" value="+2" x-model="programType" class="peer sr-only" {{ old('program_type') == '+2' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-white/20 bg-white/5 text-white/70 transition-all duration-200 peer-checked:bg-white/20 peer-checked:border-white/40 peer-checked:text-white hover:bg-white/10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span class="font-medium">+2</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="program_type" value="Degree" x-model="programType" class="peer sr-only" {{ old('program_type') == 'Degree' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-white/20 bg-white/5 text-white/70 transition-all duration-200 peer-checked:bg-white/20 peer-checked:border-white/40 peer-checked:text-white hover:bg-white/10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                    <span class="font-medium">Degree</span>
                                </div>
                            </label>
                        </div>
                        @error('program_type')
                            <p class="mt-1.5 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Class (for +2) --}}
                    <div x-show="programType === '+2'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <label for="class" class="block text-sm font-semibold text-white/90 mb-2">Class</label>
                        <select id="class" name="class" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 [&>option]:text-gray-900">
                            <option value="">Select Class</option>
                            <option value="11" {{ old('class') == '11' ? 'selected' : '' }}>Class 11</option>
                            <option value="12" {{ old('class') == '12' ? 'selected' : '' }}>Class 12</option>
                        </select>
                        @error('class')
                            <p class="mt-1.5 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Semester (for Degree) --}}
                    <div x-show="programType === 'Degree'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <label for="semester" class="block text-sm font-semibold text-white/90 mb-2">Semester</label>
                        <select id="semester" name="semester" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 [&>option]:text-gray-900">
                            <option value="">Select Semester</option>
                            @foreach(['1st Sem', '3rd Sem', '5th Sem'] as $sem)
                                <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                            @endforeach
                        </select>
                        @error('semester')
                            <p class="mt-1.5 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Roll Number --}}
                    <div>
                        <label for="roll_no" class="block text-sm font-semibold text-white/90 mb-2">Roll Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            </div>
                            <input type="text" id="roll_no" name="roll_no" value="{{ old('roll_no') }}" required class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200" placeholder="Enter your roll number">
                        </div>
                        @error('roll_no')
                            <p class="mt-1.5 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-3.5 px-4 bg-white text-indigo-700 font-bold rounded-xl hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm tracking-wide uppercase">
                        Send OTP
                    </button>
                </form>
            </div>

            {{-- Footer Link --}}
            <p class="mt-6 text-center text-sm text-indigo-200">
                Admin? <a href="{{ route('admin.login') }}" class="font-semibold text-white hover:text-indigo-100 transition-colors underline underline-offset-2">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
