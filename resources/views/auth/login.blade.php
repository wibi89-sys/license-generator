<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk — Laksana Solution</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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
<body class="min-h-full bg-[#f4f7fb] dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased font-sans flex flex-col justify-between p-4 sm:p-6 transition-colors duration-200">

    <!-- Top Right Theme Switcher (Subtle) -->
    <div class="w-full max-w-[360px] mx-auto flex justify-end pt-1">
        <button type="button" onclick="toggleTheme()"
                title="Beralih Tema (Dark/Light)"
                class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <svg class="theme-icon-sun w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="theme-icon-moon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <!-- Centered Card (Sesuai Referensi Gambar dengan Logo Resmi) -->
    <div class="my-auto w-full max-w-[360px] mx-auto">
        <div class="bg-white dark:bg-slate-900 border border-slate-100/80 dark:border-slate-800 rounded-3xl p-8 sm:p-9 shadow-[0_15px_35px_rgba(0,0,0,0.05)] dark:shadow-none transition-colors">
            
            <!-- Logo Resmi Laksana Solution -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Laksana Solution" class="h-16 w-auto max-w-[220px] object-contain">
            </div>

            <!-- Header Subtitle -->
            <div class="text-center mb-6">
                <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">
                    Masuk ke Akun Admin
                </p>
            </div>

            <!-- Error Notification (Jika Ada) -->
            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900/80 text-rose-800 dark:text-rose-300 text-xs">
                    <p class="font-medium text-center">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Form Sederhana & Elegan -->
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="remember" value="1">

                <div>
                    <label for="email" class="block text-xs font-normal text-slate-700 dark:text-slate-300 mb-1.5">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-xs font-normal text-slate-700 dark:text-slate-300 mb-1.5">
                        Password
                    </label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="w-full bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-lg text-xs font-semibold text-white bg-[#2563eb] hover:bg-[#1d4ed8] active:bg-[#1e40af] shadow-xs transition-colors cursor-pointer text-center">
                        Masuk
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-xs text-slate-400 dark:text-slate-500 pb-2">
        &copy; {{ date('Y') }} Laksana Solution
    </div>

</body>
</html>
