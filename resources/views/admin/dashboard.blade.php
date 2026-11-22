@extends('layouts.app')

@section('title', 'Dashboard — Universal License Server')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Ringkasan Sistem Lisensi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Monitoring lisensi, hardware binding (HWID), dan aktivasi perangkat klien secara realtime.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.licenses.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Terbitkan Lisensi Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 Proportional Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Licenses -->
        <div class="p-5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Lisensi</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalLicenses }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">unit</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ count($productStats) }} Produk</span>
                <span>&bull;</span>
                <span>Semua Tier Lisensi</span>
            </div>
        </div>

        <!-- Active Licenses -->
        <div class="p-5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Lisensi Aktif</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $activeLicenses }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">aktif</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                Status valid dan dapat divalidasi
            </div>
        </div>

        <!-- Bound HWID -->
        <div class="p-5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Perangkat Terikat</span>
                <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold tracking-tight text-cyan-600 dark:text-cyan-400">{{ $boundLicenses }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">hardware ID</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                Terkunci ke motherboard/CPU klien
            </div>
        </div>

        <!-- Unbound / Standby -->
        <div class="p-5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Menunggu Aktivasi</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold tracking-tight text-amber-600 dark:text-amber-400">{{ $unboundLicenses }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">kunci</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                Siap di-input pada mesin kasir/aplikasi
            </div>
        </div>

    </div>

    <!-- Product Breakdown Section -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold tracking-tight text-slate-900 dark:text-white">Distribusi Per Produk Aplikasi</h2>
            <span class="text-xs text-slate-500 dark:text-slate-400">Total 4 Ekosistem Aplikasi</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($productStats as $code => $stat)
                <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                {{ $stat['prefix'] }}-*
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $stat['active'] }} Aktif
                            </span>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">{{ $stat['label'] }}</h3>
                        <p class="font-mono text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $code }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Total: <strong class="text-slate-900 dark:text-white font-semibold">{{ $stat['total'] }}</strong></span>
                        <span class="text-slate-500 dark:text-slate-400">Terikat HWID: <strong class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $stat['bound'] }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Licenses Table Card -->
    <div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/90 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Lisensi Terbaru</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar kunci lisensi terbaru yang terdaftar pada sistem.</p>
            </div>
            <a href="{{ route('admin.licenses.index') }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                <span>Lihat Semua Lisensi ({{ $totalLicenses }})</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kunci Lisensi</th>
                        <th class="py-3 px-4">Bisnis / Pelanggan</th>
                        <th class="py-3 px-4">Tier Paket</th>
                        <th class="py-3 px-4">Perangkat (HWID)</th>
                        <th class="py-3 px-4">Masa Berlaku</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($recentLicenses as $lic)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                    {{ $lic->product_code instanceof \BackedEnum ? $lic->product_code->value : ($lic->product_code ?? 'APOTEK_POS') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <button type="button"
                                        onclick="copyToClipboard('{{ $lic->license_key }}', this)"
                                        title="Klik untuk menyalin lisensi"
                                        class="font-mono font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded hover:border-indigo-400 transition-colors cursor-pointer">
                                    {{ $lic->license_key }}
                                </button>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-100 leading-snug">{{ $lic->business_name ?? $lic->pharmacy_name }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $lic->customer_name }} &bull; Cabang: {{ $lic->branch_code }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $lic->plan_type instanceof \BackedEnum ? $lic->plan_type->value : $lic->plan_type }}
                                </span>
                                <span class="block text-[10px] text-slate-400 capitalize mt-0.5">{{ $lic->billing_type }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($lic->bound_hwid)
                                    <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>{{ $lic->bound_hostname ?? 'Terikat' }}</span>
                                    </div>
                                    <div class="font-mono text-[10px] text-slate-400 dark:text-slate-500" title="{{ $lic->bound_hwid }}">
                                        {{ substr($lic->bound_hwid, 0, 16) }}...
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Belum terikat
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($lic->billing_type === 'lifetime' || !$lic->expires_at)
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">Lifetime</span>
                                @else
                                    <div class="text-slate-800 dark:text-slate-200">{{ $lic->expires_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $lic->expires_at->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                @if ($lic->bound_hwid)
                                    <form method="POST" action="{{ route('admin.licenses.reset-hwid', $lic) }}"
                                          onsubmit="return confirm('Reset HWID lisensi {{ $lic->license_key }}? Klien dapat aktivasi di mesin baru.');" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900 dark:hover:bg-rose-900/60 transition-colors"
                                                {{ !$lic->canResetHwid() ? 'disabled' : '' }}>
                                            Reset HWID ({{ $lic->reset_used }}/{{ $lic->max_reset_count }})
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Siap Pakai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada lisensi yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Audit / Activity Logs Card -->
    <div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/90 dark:border-slate-800">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Log Aktivitas & Audit Keamanan Realtime</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Catatan riwayat aktivasi, heartbeat, dan pergantian perangkat (anti-piracy).</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Aksi</th>
                        <th class="py-3 px-4">Lisensi</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">HWID Mesin</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($recentAuditLogs as $log)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if ($log->action === 'activate')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800">
                                        Aktivasi Berhasil
                                    </span>
                                @elseif ($log->action === 'hwid_reset')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800">
                                        Reset HWID
                                    </span>
                                @elseif ($log->action === 'heartbeat')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800">
                                        Heartbeat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $log->action }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                {{ $log->license?->license_key ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-[10px] text-slate-400 select-all whitespace-nowrap">
                                {{ $log->hwid ? substr($log->hwid, 0, 16) . '...' : '-' }}
                            </td>
                            <td class="py-3 px-4 text-slate-600 dark:text-slate-400">
                                {{ $log->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada aktivitas lisensi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
