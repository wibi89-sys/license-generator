<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Apotek Cloud Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 text-3xl mb-3 shadow-lg shadow-indigo-500/10 backdrop-blur-sm">
                ☁️
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Apotek Cloud Hub</h1>
            <p class="text-xs text-slate-400 mt-1">Portal Manajemen Lisensi & Monitoring Multi-Cabang</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-2xl">
            <h2 class="text-lg font-semibold text-white mb-1">Masuk ke Akun Admin</h2>
            <p class="text-xs text-slate-400 mb-6">Silakan masukkan kredensial administrator Anda.</p>

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs flex items-start gap-2.5">
                    <span class="text-base leading-none">⚠️</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email Administrator</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-slate-900/60 border border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 transition-colors"
                            placeholder="admin@apotek.cloud">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full bg-slate-900/60 border border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 transition-colors"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 text-indigo-600 focus:ring-indigo-500/40 bg-slate-900">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full mt-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <span class="text-base leading-none">➔</span>
                </button>
            </form>

            <!-- Demo Info Banner -->
            <div class="mt-6 pt-5 border-t border-slate-700/60 text-xs text-slate-400">
                <div class="flex items-center justify-between bg-slate-900/40 border border-slate-700/40 rounded-xl p-3">
                    <div>
                        <span class="font-semibold text-slate-300 block">Akun Default:</span>
                        <code class="text-indigo-300">admin@apotek.cloud</code> / <code class="text-indigo-300">admin123</code>
                    </div>
                    <span class="text-xs bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 px-2 py-0.5 rounded-md font-mono">Dev Mode</span>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; 2026 Apotek Cloud Hub &bull; Ed25519 Cryptographic Licensing Engine
        </p>
    </div>
</body>
</html>
