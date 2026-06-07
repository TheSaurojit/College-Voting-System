<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — College Voting System</title>
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
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-pink-400/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md" x-data="otpForm()">
            {{-- Branding --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-4 shadow-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-1">Enter OTP</h1>
                <p class="text-indigo-200">OTP sent to <span class="font-semibold text-white">{{ $phone }}</span></p>
            </div>

            {{-- Glassmorphism Card --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-2xl p-8">
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-400/30">
                        <ul class="text-sm text-red-200 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.verify-otp') }}">
                    @csrf

                    {{-- Single OTP Input --}}
                    <div class="mb-8">
                        <label for="otp" class="block text-sm font-semibold text-white/90 mb-3 text-center">Enter 6-Digit OTP</label>
                        <div class="relative max-w-[240px] mx-auto">
                            <input
                                type="text"
                                id="otp"
                                name="otp"
                                x-model="otpValue"
                                @input="otpValue = $event.target.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                required
                                pattern="[0-9]{6}"
                                maxlength="6"
                                inputmode="numeric"
                                class="w-full py-3.5 bg-white/10 border-2 border-white/20 rounded-xl text-center text-3xl font-bold tracking-[0.25em] text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 transition-all duration-200"
                                placeholder="••••••"
                            >
                        </div>
                        @error('otp')
                            <p class="mt-1.5 text-sm text-red-300 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Timer --}}
                    <div class="text-center mb-6">
                        <div x-show="timeLeft > 0" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10">
                            <svg class="w-4 h-4 text-indigo-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-medium text-indigo-200">OTP expires in <span class="text-white font-bold" x-text="formattedTime"></span></span>
                        </div>
                        <div x-show="timeLeft <= 0" x-cloak>
                            <p class="text-sm text-red-300 mb-2">OTP has expired</p>
                        </div>
                    </div>

                    {{-- Verify Button --}}
                    <button type="submit" :disabled="otpValue.length !== 6" class="w-full py-3.5 px-4 bg-white text-indigo-700 font-bold rounded-xl hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm tracking-wide uppercase disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        Verify OTP
                    </button>
                </form>

                {{-- Resend OTP --}}
                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('student.send-otp') }}">
                        @csrf
                        <button type="submit" :disabled="timeLeft > 0" class="text-sm font-medium text-indigo-200 hover:text-white transition-colors disabled:opacity-40 disabled:cursor-not-allowed underline underline-offset-2">
                            Resend OTP
                        </button>
                    </form>
                </div>
            </div>

            {{-- Back Link --}}
            <p class="mt-6 text-center text-sm text-indigo-200">
                <a href="{{ route('student.login') }}" class="font-semibold text-white hover:text-indigo-100 transition-colors inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Login
                </a>
            </p>
        </div>
    </div>

    <script>
        function otpForm() {
            return {
                otpValue: '',
                timeLeft: 120,
                timer: null,

                init() {
                    this.startTimer();
                    this.$nextTick(() => {
                        const otpInput = document.getElementById('otp');
                        if (otpInput) otpInput.focus();
                    });
                },

                get formattedTime() {
                    const minutes = Math.floor(this.timeLeft / 60);
                    const seconds = this.timeLeft % 60;
                    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
                },

                startTimer() {
                    this.timer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            clearInterval(this.timer);
                        }
                    }, 1000);
                }
            };
        }
    </script>
</body>
</html>
