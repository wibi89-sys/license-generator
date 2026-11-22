<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal License Server — Kelola Seluruh Lisensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen">
    <!-- Navbar -->
    <header class="bg-slate-800/90 border-b border-slate-700/80 sticky top-0 z-30 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-xl shadow-lg shadow-indigo-500/10 hover:border-indigo-500/60 transition-colors">
                    🔑
                </a>
                <div>
                    <h1 class="text-base font-bold text-white leading-tight">Universal License Server</h1>
                    <p class="text-xs text-slate-400">Pusat Manajemen Lisensi & Pengaturan HWID</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-300 hover:text-white bg-slate-700/60 hover:bg-slate-700 border border-slate-600/80 px-3.5 py-2 rounded-xl transition-colors">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.licenses.create') }}" class="text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 px-3.5 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/20 flex items-center gap-1.5">
                    <span>+</span>
                    <span>Terbitkan Lisensi</span>
                </a>
                <div class="border-l border-slate-700 pl-3">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-rose-400 hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-300 text-xs flex items-center gap-3 shadow-lg">
                <span class="text-base">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-300 text-xs flex items-center gap-3 shadow-lg">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter & Search Toolbar -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.licenses.index') }}" class="text-xs px-3 py-1.5 rounded-xl border transition-colors {{ !request('product') ? 'bg-indigo-600 border-indigo-500 text-white font-semibold' : 'bg-slate-900 border-slate-700 text-slate-400 hover:text-white' }}">
                    Semua Produk
                </a>
                @foreach ($products as $prod)
                    <a href="{{ route('admin.licenses.index', ['product' => $prod->value, 'search' => request('search')]) }}"
                        class="text-xs px-3 py-1.5 rounded-xl border transition-colors {{ request('product') === $prod->value ? 'bg-indigo-600 border-indigo-500 text-white font-semibold' : 'bg-slate-900 border-slate-700 text-slate-400 hover:text-white' }}">
                        {{ $prod->label() }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.licenses.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @if(request('product'))
                    <input type="hidden" name="product" value="{{ request('product') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lisensi, bisnis, customer, host..."
                    class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-1.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 w-full sm:w-64">
                <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-white text-xs px-3 py-1.5 rounded-xl font-medium">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700/80 text-xs">
                    <thead>
                        <tr class="text-slate-400 font-semibold uppercase tracking-wider text-left">
                            <th class="py-3 px-3">Produk</th>
                            <th class="py-3 px-3">Kunci Lisensi</th>
                            <th class="py-3 px-3">Nama Bisnis / Customer</th>
                            <th class="py-3 px-3">Paket (Tier)</th>
                            <th class="py-3 px-3">Status Mesin (HWID)</th>
                            <th class="py-3 px-3">Reset Digunakan</th>
                            <th class="py-3 px-3">Masa Berlaku</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($licenses as $lic)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="py-3 px-3">
                                    <span class="font-mono font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded">
                                        {{ $lic->product_code instanceof \BackedEnum ? $lic->product_code->value : ($lic->product_code ?? 'APOTEK_POS') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-mono font-bold text-white bg-slate-900/90 px-2.5 py-1 rounded border border-slate-700 select-all">
                                        {{ $lic->license_key }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-100">{{ $lic->business_name ?? $lic->pharmacy_name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $lic->customer_name }} &bull; Cabang: {{ $lic->branch_code }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-700 text-slate-200">
                                        {{ $lic->plan_type instanceof \BackedEnum ? $lic->plan_type->value : $lic->plan_type }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 capitalize mt-0.5">{{ $lic->billing_type }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($lic->bound_hwid)
                                        <div class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            <span>{{ $lic->bound_hostname ?? 'Terikat' }}</span>
                                        </div>
                                        <div class="font-mono text-[10px] text-slate-400 select-all" title="{{ $lic->bound_hwid }}">
                                            {{ substr($lic->bound_hwid, 0, 16) }}...
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-400 font-medium">
                                            <span>⏳</span> Belum terikat
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-300">
                                    <span class="font-mono">{{ $lic->reset_used }}</span> / {{ $lic->max_reset_count }}x
                                </td>
                                <td class="py-3 px-3">
                                    @if ($lic->billing_type === 'lifetime' || !$lic->expires_at)
                                        <span class="text-indigo-400 font-semibold">Lifetime (Selamanya)</span>
                                    @else
                                        <div class="text-slate-200">{{ $lic->expires_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $lic->expires_at->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if ($lic->bound_hwid)
                                        <form method="POST" action="{{ route('admin.licenses.reset-hwid', $lic) }}" onsubmit="return confirm('Yakin ingin mereset HWID lisensi {{ $lic->license_key }}? Klien harus aktivasi ulang.');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-rose-300 hover:text-white bg-rose-500/20 hover:bg-rose-600 border border-rose-500/30 px-2.5 py-1 rounded-lg transition-colors cursor-pointer" {{ !$lic->canResetHwid() ? 'disabled' : '' }}>
                                                Reset HWID
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-emerald-400/80 font-medium">Siap Aktivasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    Tidak ada lisensi yang cocok dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-700/80">
                {{ $licenses->links() }}
            </div>
        </div>
    </main>

    <footer class="border-t border-slate-800 mt-12 py-6 text-center text-xs text-slate-500">
        &copy; 2026 Universal License Server &bull; Ed25519 Cryptographic Licensing Engine
    </footer>
</body>
</html>
