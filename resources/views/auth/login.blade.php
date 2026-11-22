<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk — Universal License Server | Laksana Solution</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    }
                }
            }
        };

        (function() {
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon-sun').forEach(el => el.classList.toggle('hidden', !isDark));
            document.querySelectorAll('.theme-icon-moon').forEach(el => el.classList.toggle('hidden', isDark));
        }

        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                pwdInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
    <style>
        .grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="h-full bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased font-sans transition-colors duration-200">

    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- LEFT PANEL: Enterprise Login Form -->
        <div class="flex-1 flex flex-col justify-between px-6 py-10 sm:px-12 lg:px-16 xl:px-24 bg-white dark:bg-slate-950 transition-colors">
            
            <!-- Top Branding & Theme Switcher -->
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center font-bold tracking-tight shadow-sm transition-transform group-hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold tracking-tight text-slate-900 dark:text-white block leading-tight">Laksana Solution</span>
                        <span class="text-[11px] font-mono font-medium text-slate-500 dark:text-slate-400">License Management Hub</span>
                    </div>
                </a>

                <!-- Theme Toggle Button -->
                <button type="button" onclick="toggleTheme()"
                        title="Ubah Tema"
                        class="p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-900 border border-slate-200 dark:border-slate-800 transition-colors">
                    <svg class="theme-icon-sun w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="theme-icon-moon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>

            <!-- Main Form Section -->
            <div class="my-auto py-12 max-w-sm w-full mx-auto">
                
                <div class="mb-8">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Masuk ke Akun Admin</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                        Akses konsol penerbitan lisensi dan pengikatan perangkat mesin klien.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border-l-4 border-rose-500 text-rose-900 dark:bg-rose-950/40 dark:border-rose-500 dark:text-rose-200 text-xs">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <p class="font-medium">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Email Administrator
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               placeholder="admin@laksanatech.com"
                               class="w-full bg-slate-50/60 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white focus:border-slate-900 dark:focus:border-white transition-all shadow-2xs">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Kata Sandi
                            </label>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full bg-slate-50/60 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-800 rounded-lg pl-3.5 pr-10 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white focus:border-slate-900 dark:focus:border-white transition-all shadow-2xs">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <svg id="eye-open" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 dark:text-slate-400 select-none">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-700 text-slate-900 focus:ring-slate-900 bg-white dark:bg-slate-900">
                            <span>Ingat sesi perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100 shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all cursor-pointer">
                            <span>Masuk ke Konsol</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Security Assurance Footer -->
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-900 flex items-center justify-center gap-2 text-[11px] text-slate-400 dark:text-slate-500">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Terproteksi Kriptografi Ed25519 &bull; Rate Limiting Aktif</span>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="text-xs text-slate-400 dark:text-slate-500 text-center sm:text-left">
                &copy; {{ date('Y') }} Laksana Solution
            </div>

        </div>

        <!-- RIGHT PANEL: Enterprise Technical Showcase (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-slate-950 relative overflow-hidden flex-col justify-between p-12 xl:p-16 border-l border-slate-800">
            
            <!-- Background Matrix Pattern -->
            <div class="absolute inset-0 grid-pattern opacity-40"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl pointer-events-none"></div>

            <!-- Top Tag -->
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-mono font-semibold tracking-wider uppercase bg-slate-900 text-indigo-400 border border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    Universal Licensing Infrastructure
                </span>
            </div>

            <!-- Center Showcase Card (Engine Overview) -->
            <div class="relative z-10 max-w-md my-auto space-y-6">
                
                <div>
                    <h2 class="text-2xl xl:text-3xl font-extrabold text-white tracking-tight leading-tight">
                        Pusat Kontrol Lisensi & Proteksi Anti-Pembajakan.
                    </h2>
                    <p class="text-xs text-slate-400 mt-2.5 leading-relaxed">
                        Arsitektur penandatanganan sertifikat digital berkecepatan tinggi dengan verifikasi mesin offline tanpa ketergantungan koneksi terus-menerus.
                    </p>
                </div>

                <!-- Technical Spec Card -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-5 shadow-2xl backdrop-blur-md space-y-4">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="font-mono text-xs font-semibold text-slate-200">ENGINE ACTIVE</span>
                        </div>
                        <span class="font-mono text-[10px] text-slate-400">Ed25519 (RFC 8032)</span>
                    </div>

                    <div class="space-y-2.5 font-mono text-[11px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Hardware Binding:</span>
                            <span class="text-slate-200">SHA-256 CPU + BIOS + OS HWID</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Verifikasi Klien:</span>
                            <span class="text-emerald-400">Zero-Latency / Offline Ready</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Ekosistem Produk:</span>
                            <span class="text-slate-200">Apotek, Retail POS, Absensi, Klinik</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Integritas Sertifikat:</span>
                        <span class="text-indigo-400 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Tamper-Proof
                        </span>
                    </div>

                </div>

            </div>

            <!-- Bottom Stats -->
            <div class="relative z-10 pt-6 border-t border-slate-800/80 flex items-center justify-between text-slate-400 text-xs">
                <div>
                    <span class="block text-white font-bold text-sm">Enterprise Grade</span>
                    <span class="text-[11px] text-slate-500">Multi-Cabang & Multi-Mesin</span>
                </div>
                <div class="text-right">
                    <span class="block text-white font-bold text-sm">Offline First</span>
                    <span class="text-[11px] text-slate-500">Validasi Mandiri di Klien</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
