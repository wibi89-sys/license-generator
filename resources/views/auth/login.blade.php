<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Universal License Server</title>
    
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

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans flex flex-col justify-center py-12 sm:px-6 lg:px-8 transition-colors duration-200">

    <!-- Top Right Theme Switcher -->
    <div class="absolute top-5 right-5">
        <button type="button" onclick="toggleTheme()"
                title="Beralih Tema (Dark/Light)"
                class="p-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-200/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition-colors">
            <svg class="theme-icon-sun w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="theme-icon-moon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo & Header -->
        <div class="flex justify-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-xl font-bold tracking-tight text-slate-900 dark:text-white">
            Universal License Server
        </h2>
        <p class="mt-1 text-center text-xs text-slate-500 dark:text-slate-400">
            Pusat Penerbitan & Aktivasi Lisensi Ed25519
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white dark:bg-slate-900 py-8 px-6 sm:px-8 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-xs transition-colors">
            
            <div class="mb-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Masuk ke Akun Admin</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Masukkan kredensial administrator Anda untuk melanjutkan.</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900/80 text-rose-800 dark:text-rose-300 text-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
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
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@apotek.cloud"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Kata Sandi
                    </label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 dark:text-slate-400 select-none">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-slate-50 dark:bg-slate-800">
                        <span>Ingat sesi masuk</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Dev Demo Helper -->
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex items-center justify-between text-[11px] p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60">
                    <div class="text-slate-600 dark:text-slate-400">
                        <span class="font-semibold text-slate-700 dark:text-slate-300">Akun Default:</span>
                        <code class="font-mono text-indigo-600 dark:text-indigo-400">admin@apotek.cloud</code> / <code class="font-mono text-indigo-600 dark:text-indigo-400">admin123</code>
                    </div>
                </div>
            </div>

        </div>

        <p class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} Universal License Server &bull; Ed25519 Cryptographic Licensing
        </p>
    </div>

</body>
</html>
