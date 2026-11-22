<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal License Server — Dashboard Manajemen Lisensi</title>
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
                <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-xl shadow-lg shadow-indigo-500/10">
                    🔑
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold text-white leading-tight">Universal License Server</h1>
                        <span class="text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-full uppercase tracking-wider">Ed25519 Engine</span>
                    </div>
                    <p class="text-xs text-slate-400">Pusat Penerbitan, Hardware Binding & Aktivasi Lisensi Multi-Aplikasi</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.licenses.create') }}" class="text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 px-3.5 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/20 flex items-center gap-1.5">
                    <span>+</span>
                    <span>Terbitkan Lisensi</span>
                </a>
                <a href="{{ route('admin.licenses.index') }}" class="text-xs font-semibold text-slate-300 hover:text-white bg-slate-700/60 hover:bg-slate-700 border border-slate-600/80 px-3 py-2 rounded-xl transition-colors">
                    Daftar Semua Lisensi
                </a>
                <div class="border-l border-slate-700 pl-3 flex items-center gap-3">
                    <span class="text-xs text-slate-400 hidden sm:inline">
                        Admin: <strong class="text-slate-200">{{ auth()->user()->name ?? 'Administrator' }}</strong>
                    </span>
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

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- 4 Top Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Licenses -->
            <div class="bg-slate-800/80 border border-slate-700/80 p-5 rounded-2xl shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Lisensi</span>
                    <span class="text-lg">📜</span>
                </div>
                <p class="text-3xl font-extrabold text-white mt-2">{{ $totalLicenses }}</p>
                <div class="mt-2 flex items-center gap-2 text-xs text-slate-400">
                    <span class="text-indigo-400 font-semibold">{{ count($productStats) }} Jenis Produk</span>
                    <span>&bull;</span>
                    <span>Semua Tier</span>
                </div>
            </div>

            <!-- Active Licenses -->
            <div class="bg-slate-800/80 border border-slate-700/80 p-5 rounded-2xl shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Lisensi Aktif</span>
                    <span class="text-lg">🟢</span>
                </div>
                <p class="text-3xl font-extrabold text-emerald-400 mt-2">{{ $activeLicenses }}</p>
                <div class="mt-2 text-xs text-slate-400">
                    Status siap pakai & beroperasi
                </div>
            </div>

            <!-- Bound HWID -->
            <div class="bg-slate-800/80 border border-slate-700/80 p-5 rounded-2xl shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-cyan-400">Perangkat Terikat (HWID)</span>
                    <span class="text-lg">💻</span>
                </div>
                <p class="text-3xl font-extrabold text-cyan-400 mt-2">{{ $boundLicenses }}</p>
                <div class="mt-2 text-xs text-slate-400">
                    Terkunci aman ke mesin klien
                </div>
            </div>

            <!-- Unbound / Ready to Activate -->
            <div class="bg-slate-800/80 border border-slate-700/80 p-5 rounded-2xl shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-400">Siap Diaktivasi</span>
                    <span class="text-lg">⏳</span>
                </div>
                <p class="text-3xl font-extrabold text-amber-400 mt-2">{{ $unboundLicenses }}</p>
                <div class="mt-2 text-xs text-slate-400">
                    Belum terikat ke perangkat apapun
                </div>
            </div>
        </div>

        <!-- Section: Distribusi Produk Aplikasi -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Distribusi Lisensi Berdasarkan Produk</h2>
                    <p class="text-xs text-slate-400">Satu sistem lisensi terpadu untuk seluruh portofolio aplikasi Anda</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($productStats as $code => $stat)
                    <div class="bg-slate-800/60 border border-slate-700/70 p-5 rounded-2xl hover:border-indigo-500/40 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-mono font-bold bg-slate-900 border border-slate-700 text-indigo-300 px-2.5 py-1 rounded-lg">
                                Prefix: {{ $stat['prefix'] }}-*
                            </span>
                            <span class="text-xs font-semibold text-emerald-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                {{ $stat['active'] }} Aktif
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">{{ $stat['label'] }}</h3>
                        <p class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $code }}</p>

                        <div class="mt-4 pt-3 border-t border-slate-700/60 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Total Lisensi: <strong class="text-white">{{ $stat['total'] }}</strong></span>
                            <span class="text-slate-400">Terikat HWID: <strong class="text-cyan-300">{{ $stat['bound'] }}</strong></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section: Lisensi Terbaru Diterbitkan -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-bold text-white">Lisensi Terbaru Diterbitkan</h2>
                    <p class="text-xs text-slate-400">Daftar lisensi terkini beserta status aktivasi mesin klien</p>
                </div>
                <a href="{{ route('admin.licenses.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                    <span>Lihat Semua ({{ $totalLicenses }})</span>
                    <span>➔</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700/80 text-xs">
                    <thead>
                        <tr class="text-slate-400 font-semibold uppercase tracking-wider text-left">
                            <th class="py-3 px-3">Produk</th>
                            <th class="py-3 px-3">Kunci Lisensi</th>
                            <th class="py-3 px-3">Nama Bisnis / Customer</th>
                            <th class="py-3 px-3">Paket (Tier)</th>
                            <th class="py-3 px-3">Status Mesin (HWID)</th>
                            <th class="py-3 px-3">Masa Berlaku</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse ($recentLicenses as $lic)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="py-3 px-3">
                                    <span class="font-mono font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded">
                                        {{ $lic->product_code instanceof \BackedEnum ? $lic->product_code->value : ($lic->product_code ?? 'APOTEK_POS') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-mono font-bold text-white bg-slate-900/90 px-2 py-1 rounded border border-slate-700 select-all">
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
                                        <form method="POST" action="{{ route('admin.licenses.reset-hwid', $lic) }}" onsubmit="return confirm('Reset HWID lisensi {{ $lic->license_key }}? Mesin kasir klien harus aktivasi ulang.');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-semibold text-rose-300 hover:text-white bg-rose-500/20 hover:bg-rose-600 border border-rose-500/30 px-2.5 py-1 rounded-lg transition-colors cursor-pointer" {{ !$lic->canResetHwid() ? 'disabled' : '' }}>
                                                Reset HWID ({{ $lic->reset_used }}/{{ $lic->max_reset_count }})
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-500 italic">Siap Dipakai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada lisensi yang dibuat. Klik tombol "Terbitkan Lisensi" di atas untuk membuat lisensi baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Real-time Activation & Audit Logs -->
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>🛡️</span> Log Aktivitas & Audit Keamanan Realtime
                    </h2>
                    <p class="text-xs text-slate-400">Catatan riwayat aktivasi, heartbeat, dan pergantian perangkat (anti-piracy)</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700/80 text-xs">
                    <thead>
                        <tr class="text-slate-400 font-semibold uppercase tracking-wider text-left">
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Aksi</th>
                            <th class="py-3 px-3">Lisensi Terkait</th>
                            <th class="py-3 px-3">IP Address</th>
                            <th class="py-3 px-3">HWID Mesin</th>
                            <th class="py-3 px-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse ($recentAuditLogs as $log)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="py-3 px-3 text-slate-300 whitespace-nowrap">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    @if ($log->action === 'activate')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            Aktivasi Berhasil
                                        </span>
                                    @elseif ($log->action === 'hwid_reset')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Reset HWID
                                        </span>
                                    @elseif ($log->action === 'heartbeat')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                            Heartbeat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-700 text-slate-200">
                                            {{ $log->action }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-white">
                                    {{ $log->license?->license_key ?? '-' }}
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-300">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="py-3 px-3 font-mono text-[10px] text-slate-400 select-all">
                                    {{ $log->hwid ? substr($log->hwid, 0, 16) . '...' : '-' }}
                                </td>
                                <td class="py-3 px-3 text-slate-300">
                                    {{ $log->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Belum ada log aktivitas aktivasi tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="border-t border-slate-800 mt-12 py-6 text-center text-xs text-slate-500">
        &copy; 2026 Universal License Server &bull; Ed25519 Cryptographic Licensing Engine &bull; Zero Dependency Offline Verification
    </footer>
</body>
</html>
