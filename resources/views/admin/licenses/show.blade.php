@extends('layouts.app')

@section('title', 'Detail Lisensi ' . $license->license_key . ' — Universal License Server')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('admin.licenses.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    &larr; Kembali ke Daftar Lisensi
                </a>
                <span>&bull;</span>
                <span class="font-mono">{{ $product->label() }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white font-mono">
                    {{ $license->license_key }}
                </h1>
                <button type="button"
                        onclick="copyToClipboard('{{ $license->license_key }}', this)"
                        class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors">
                    Salin Kunci
                </button>

                <!-- Status Badge -->
                @if($license->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                @elseif($license->status === 'suspended')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Ditangguhkan (Suspended)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Dicabut (Revoked)
                    </span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Edit Button -->
            <a href="{{ route('admin.licenses.edit', $license) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <span>Edit Lisensi</span>
            </a>

            <!-- Download .lic Offline Certificate -->
            @if(!empty($license->bound_hwid))
                <a href="{{ route('admin.licenses.download-lic', $license) }}"
                   title="Unduh file sertifikat lisensi digital untuk aktivasi offline"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-900 transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Unduh File Lisensi (.lic)</span>
                </a>
            @else
                <button type="button"
                        onclick="let h = prompt('Lisensi ini belum terikat HWID. Masukkan Hardware ID (HWID) komputer klien tujuan:'); if(h && h.trim().length > 5) { window.location.href = '{{ route('admin.licenses.download-lic', $license) }}?hwid=' + encodeURIComponent(h.trim()); }"
                        title="Generate file lisensi offline dengan menentukan HWID komputer klien"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-900 transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Generate File Lisensi (.lic)</span>
                </button>
            @endif

            <!-- Reset HWID -->
            @if($license->bound_hwid)
                <form method="POST" action="{{ route('admin.licenses.reset-hwid', $license) }}"
                      onsubmit="return confirm('Reset ikatan HWID untuk lisensi ini? Mesin klien saat ini akan dilepas dan dapat diaktivasi pada perangkat baru.');">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 transition-colors"
                            {{ !$license->canResetHwid() ? 'disabled' : '' }}>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Reset HWID ({{ $license->reset_used }}/{{ $license->max_reset_count }})</span>
                    </button>
                </form>
            @endif

            <!-- Toggle Status (Suspend / Activate) -->
            <form method="POST" action="{{ route('admin.licenses.toggle-status', $license) }}">
                @csrf
                @if($license->status === 'active')
                    <input type="hidden" name="status" value="suspended">
                    <button type="submit" onclick="return confirm('Tangguhkan lisensi ini? Klien tidak akan dapat menggunakannya sementara waktu.');"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 transition-colors">
                        Tangguhkan
                    </button>
                @else
                    <input type="hidden" name="status" value="active">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 transition-colors">
                        Aktifkan Kembali
                    </button>
                @endif
            </form>

            <!-- Delete Button -->
            <form method="POST" action="{{ route('admin.licenses.destroy', $license) }}"
                  onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS lisensi {{ $license->license_key }} secara permanen? Data yang dihapus tidak dapat dikembalikan.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-900 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card: Info Bisnis & Pelanggan -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Informasi Bisnis & Paket Lisensi</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Nama Bisnis / Instansi</span>
                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $license->business_name ?? $license->pharmacy_name }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Nama Pemilik / PIC</span>
                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $license->customer_name }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Kode Cabang / Outlet</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono">{{ $license->branch_code }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Target Produk Aplikasi</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $product->label() }}</span>
                        <span class="text-[10px] text-slate-400 block font-mono">({{ $product->value }})</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Tier Paket</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono">
                            {{ $license->plan_type instanceof \BackedEnum ? $license->plan_type->value : $license->plan_type }}
                        </span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 block text-[11px]">Model Tagihan & Masa Berlaku</span>
                        <span class="font-bold text-slate-900 dark:text-white capitalize">{{ $license->billing_type }}</span>
                        <span class="text-slate-500 block text-[11px]">
                            @if($license->billing_type === 'lifetime' || !$license->expires_at)
                                Lifetime (Aktif Selamanya)
                            @else
                                Berakhir: {{ $license->expires_at->format('d M Y') }} ({{ $license->expires_at->diffForHumans() }})
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card: Hardware Binding (HWID) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Pengikatan Perangkat (Hardware Binding / HWID)</span>
                </h2>

                <div class="space-y-3 text-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 gap-2">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Status Kaitan Mesin</span>
                            @if($license->bound_hwid)
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Terikat pada mesin: {{ $license->bound_hostname ?? 'Hostname tidak terlacak' }}
                                </span>
                            @else
                                <span class="font-bold text-amber-600 dark:text-amber-400 inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    Belum Terikat (Menunggu aktivasi pertama dari aplikasi klien)
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[11px]">Kuota Reset HWID</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">
                                {{ $license->reset_used }} dari {{ $license->max_reset_count }}x digunakan
                            </span>
                        </div>
                    </div>

                    @if($license->bound_hwid)
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-slate-400 text-[11px]">Hash HWID Mesin</span>
                                <button type="button" onclick="copyToClipboard('{{ $license->bound_hwid }}', this)"
                                        class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                                    Salin HWID
                                </button>
                            </div>
                            <div class="font-mono text-slate-800 dark:text-slate-200 text-[11px] break-all bg-white dark:bg-slate-900 p-2 rounded border border-slate-200 dark:border-slate-700">
                                {{ $license->bound_hwid }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Pertama Kali Diaktivasi</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $license->activated_at ? $license->activated_at->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Heartbeat Terakhir</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $license->last_heartbeat_at ? $license->last_heartbeat_at->diffForHumans() : '-' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card: Fitur & Batasan Sumber Daya -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Fitur Aktif & Kuota Limits</span>
                </h2>

                <div class="space-y-4">
                    <!-- Features Badges -->
                    <div>
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">Modul Fitur yang Diizinkan:</span>
                        <div class="flex flex-wrap gap-2">
                            @if(!empty($license->features_json))
                                @foreach($license->features_json as $featKey => $featEnabled)
                                    @if($featEnabled)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ ucwords(str_replace('_', ' ', $featKey)) }}
                                        </span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-xs text-slate-400">Tidak ada konfigurasi fitur spesifik.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Limits Badges -->
                    @if(!empty($license->limits_json))
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">Batasan Kuota:</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach($license->limits_json as $limitKey => $limitVal)
                                    <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-center">
                                        <span class="text-[10px] text-slate-400 block uppercase font-mono">{{ str_replace('_', ' ', $limitKey) }}</span>
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                            {{ $limitVal === -1 ? 'Unlimited' : $limitVal }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: .env Integration & Audit Trail (1 Col) -->
        <div class="space-y-6">

            <!-- Card: Konfigurasi Klien (.env) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        <span>Konfigurasi Klien (.env)</span>
                    </h2>
                    <button type="button"
                            onclick="copyToClipboard(document.getElementById('envSnippet').innerText, this)"
                            class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                        Salin Semua
                    </button>
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                    Tempelkan baris konfigurasi berikut ke file <code class="font-mono text-indigo-600 dark:text-indigo-400">.env</code> pada aplikasi klien Anda:
                </p>

                <div class="relative bg-slate-900 text-slate-100 p-3.5 rounded-xl text-xs font-mono overflow-x-auto shadow-inner">
                    <pre id="envSnippet"><code># Universal License Configuration
LICENSE_SERVER_URL={{ url('/') }}
LICENSE_PRODUCT_CODE={{ $product->value }}
LICENSE_KEY={{ $license->license_key }}
LICENSE_BRANCH_CODE={{ $license->branch_code }}</code></pre>
                </div>
            </div>

            <!-- Card: Riwayat Audit (Audit Trail) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat Audit Lisensi</span>
                </h2>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    @forelse($license->auditLogs as $log)
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200 uppercase text-[10px] px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-700">
                                    {{ $log->action }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    {{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-snug">
                                {{ $log->notes ?? 'Tidak ada catatan tambahan.' }}
                            </p>
                            @if($log->ip_address)
                                <div class="mt-1.5 text-[10px] text-slate-400 font-mono">
                                    IP: {{ $log->ip_address }} @if($log->app_version) &bull; v{{ $log->app_version }} @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">
                            Belum ada riwayat aktivitas untuk lisensi ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
