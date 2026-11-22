@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi & Lisensi — Universal License Server')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="pb-4 border-b border-slate-200/80 dark:border-slate-800">
        <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Pengaturan Aplikasi & Lisensi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
            Kelola branding aplikasi, konfigurasi kriptografi Ed25519, kuota default, dan integrasi API aplikasi klien.
        </p>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900 text-rose-800 dark:text-rose-300 text-xs">
            <span class="font-bold block mb-1">Terjadi kesalahan validasi:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Identitas & Branding Aplikasi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        1. Identitas Aplikasi & Branding
                    </h2>
                    <p class="text-[11px] text-slate-400">Pengaturan nama sistem yang tampil pada login, navbar, dan hak cipta.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="app_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Aplikasi Sistem <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="app_name" name="app_name"
                           value="{{ old('app_name', $settings['app_name']) }}" required
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="company_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Perusahaan / Vendor <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="company_name" name="company_name"
                           value="{{ old('company_name', $settings['company_name']) }}" required
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="app_tagline" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Tagline / Subjudul Aplikasi
                    </label>
                    <input type="text" id="app_tagline" name="app_tagline"
                           value="{{ old('app_tagline', $settings['app_tagline']) }}"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="footer_text" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Teks Footer Hak Cipta
                    </label>
                    <input type="text" id="footer_text" name="footer_text"
                           value="{{ old('footer_text', $settings['footer_text']) }}"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Logo Upload & Preview -->
                <div class="sm:col-span-2 pt-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Logo Resmi Aplikasi
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800">
                        <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Saat Ini" class="h-12 w-auto object-contain">
                        </div>
                        <div class="flex-1 space-y-1">
                            <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                   class="text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-300 cursor-pointer">
                            <p class="text-[11px] text-slate-400">
                                Format didukung: PNG transparan, SVG, JPG, WEBP. Maksimal 2MB. Logo akan langsung diterapkan di seluruh sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Kriptografi Ed25519 & Default Lisensi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        2. Kriptografi Ed25519 & Aturan Default
                    </h2>
                    <p class="text-[11px] text-slate-400">Parameter keamanan dan nilai baku saat lisensi baru diterbitkan.</p>
                </div>
            </div>

            <!-- Public Key Box -->
            <div class="mb-5 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Ed25519 Public Verification Key
                    </span>
                    <button type="button" onclick="copyToClipboard('{{ $publicKey }}', this)"
                            class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Salin Kunci Publik
                    </button>
                </div>
                <div class="font-mono text-xs bg-white dark:bg-slate-900 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 break-all select-all">
                    {{ $publicKey }}
                </div>
                <p class="text-[11px] text-slate-400 mt-2">
                    Kunci publik ini dibagikan ke aplikasi klien (misal POS Apotek, Retail POS). Klien menggunakan kunci ini untuk memverifikasi keaslian lisensi secara offline tanpa perlu terhubung ke database.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="default_reset_limit" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Default Kuota Reset HWID
                    </label>
                    <input type="number" id="default_reset_limit" name="default_reset_limit" min="0" max="99"
                           value="{{ old('default_reset_limit', $settings['default_reset_limit']) }}" required
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Batas ganti mesin klien</span>
                </div>

                <div>
                    <label for="default_grace_days" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Masa Tenggang (Grace Days)
                    </label>
                    <input type="number" id="default_grace_days" name="default_grace_days" min="0" max="365"
                           value="{{ old('default_grace_days', $settings['default_grace_days']) }}" required
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Hari toleransi lewat tempo</span>
                </div>

                <div>
                    <label for="default_heartbeat_days" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Interval Heartbeat (Hari)
                    </label>
                    <input type="number" id="default_heartbeat_days" name="default_heartbeat_days" min="1" max="90"
                           value="{{ old('default_heartbeat_days', $settings['default_heartbeat_days']) }}" required
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Frekuensi sinkron berkala</span>
                </div>

                <div>
                    <label for="server_public_url" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        URL Publik Server
                    </label>
                    <input type="url" id="server_public_url" name="server_public_url"
                           value="{{ old('server_public_url', $settings['server_public_url']) }}"
                           class="w-full font-mono bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <span class="text-[10px] text-slate-400 mt-1 block">Domain endpoint aktivasi</span>
                </div>
            </div>
        </div>

        <!-- Submit Button for Settings -->
        <div class="flex items-center justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 transition-all cursor-pointer">
                Simpan Perubahan Pengaturan
            </button>
        </div>
    </form>

    <!-- Card 3: Integrasi API Klien & Webhook -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        3. Kredensial API & Dokumentasi Endpoint Klien
                    </h2>
                    <p class="text-[11px] text-slate-400">Koneksi backend eksternal dan API komunikasi mesin klien.</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.settings.regenerate-token') }}"
                  onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin meng-generate ulang Master API Token? Sistem eksternal yang menggunakan token lama akan terputus.');">
                @csrf
                <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900 transition-colors">
                    Generate Ulang API Token
                </button>
            </form>
        </div>

        <!-- Master API Token Box -->
        <div class="mb-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                    Master API Bearer Token
                </span>
                <button type="button" onclick="copyToClipboard('{{ $settings['master_api_token'] }}', this)"
                        class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Salin Token
                </button>
            </div>
            <div class="font-mono text-xs bg-white dark:bg-slate-900 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 break-all select-all">
                {{ $settings['master_api_token'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-2">
                Gunakan token ini sebagai header <code class="font-mono text-indigo-600 dark:text-indigo-400">Authorization: Bearer [TOKEN]</code> bila ingin menghubungkan sistem billing atau website payment gateway untuk auto-generate lisensi.
            </p>
        </div>

        <!-- Endpoint List Table -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                Daftar Endpoint API Siap Pakai:
            </h3>

            <div class="overflow-x-auto rounded-xl border border-slate-200/80 dark:border-slate-800">
                <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500 font-semibold text-[11px]">
                        <tr>
                            <th class="py-2.5 px-3">Metode</th>
                            <th class="py-2.5 px-3">Endpoint URL</th>
                            <th class="py-2.5 px-3">Fungsi</th>
                            <th class="py-2.5 px-3">Otentikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-mono text-[11px]">
                        <tr>
                            <td class="py-2.5 px-3"><span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold">POST</span></td>
                            <td class="py-2.5 px-3 text-slate-900 dark:text-slate-100">/api/v1/licenses/activate</td>
                            <td class="py-2.5 px-3 font-sans text-slate-600 dark:text-slate-400">Aktivasi pertama kali & mengikat HWID mesin</td>
                            <td class="py-2.5 px-3 font-sans text-slate-500">License Key</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-3"><span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold">POST</span></td>
                            <td class="py-2.5 px-3 text-slate-900 dark:text-slate-100">/api/v1/licenses/heartbeat</td>
                            <td class="py-2.5 px-3 font-sans text-slate-600 dark:text-slate-400">Pembaruan payload token & cek status berkala</td>
                            <td class="py-2.5 px-3 font-sans text-slate-500">License Key + HWID</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-3"><span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 font-bold">GET</span></td>
                            <td class="py-2.5 px-3 text-slate-900 dark:text-slate-100">/api/v1/licenses/public-key</td>
                            <td class="py-2.5 px-3 font-sans text-slate-600 dark:text-slate-400">Mengambil Ed25519 public key secara dinamis</td>
                            <td class="py-2.5 px-3 font-sans text-slate-500">Publik</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
