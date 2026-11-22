@extends('layouts.app')

@section('title', 'Manajemen Lisensi — Universal License Server')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Daftar Semua Lisensi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola lisensi, status pengikatan hardware klien, dan reset limit aktivasi.</p>
        </div>
        <div>
            <a href="{{ route('admin.licenses.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Terbitkan Lisensi Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Product Filter Tabs -->
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('admin.licenses.index', ['search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border {{ !request('product') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 dark:bg-indigo-950/70 dark:border-indigo-800 dark:text-indigo-300' : 'bg-transparent border-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                Semua Produk
            </a>
            @foreach ($products as $prod)
                <a href="{{ route('admin.licenses.index', ['product' => $prod->value, 'search' => request('search')]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border {{ request('product') === $prod->value ? 'bg-indigo-50 border-indigo-200 text-indigo-700 dark:bg-indigo-950/70 dark:border-indigo-800 dark:text-indigo-300' : 'bg-transparent border-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    {{ $prod->label() }}
                </a>
            @endforeach
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.licenses.index') }}" class="flex items-center gap-2 w-full md:w-auto">
            @if(request('product'))
                <input type="hidden" name="product" value="{{ request('product') }}">
            @endif
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari lisensi, bisnis, customer..."
                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kunci Lisensi</th>
                        <th class="py-3 px-4">Bisnis & Pelanggan</th>
                        <th class="py-3 px-4">Tier Paket</th>
                        <th class="py-3 px-4">Hardware Terikat (HWID)</th>
                        <th class="py-3 px-4">Reset Digunakan</th>
                        <th class="py-3 px-4">Masa Berlaku</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($licenses as $lic)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                    {{ $lic->product_code instanceof \BackedEnum ? $lic->product_code->value : ($lic->product_code ?? 'APOTEK_POS') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <button type="button"
                                        onclick="copyToClipboard('{{ $lic->license_key }}', this)"
                                        title="Klik untuk menyalin"
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
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-600 dark:text-slate-300">
                                <span class="font-mono">{{ $lic->reset_used }}</span> / {{ $lic->max_reset_count }}x
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
                                            Reset HWID
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400/80 font-medium">Siap Aktivasi</span>
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

        @if($licenses->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
