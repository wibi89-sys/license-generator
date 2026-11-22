@extends('layouts.app')

@section('title', 'Terbitkan Lisensi Baru — Universal License Server')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Terbitkan Lisensi Baru</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Generate lisensi kriptografis Ed25519 sesuai target produk dan tier pelanggan.</p>
        </div>
        <a href="{{ route('admin.licenses.index') }}"
           class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs p-6 sm:p-8">
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900/80 text-rose-800 dark:text-rose-300 text-xs">
                <span class="font-bold block mb-1">Terjadi kesalahan validasi:</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.licenses.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Target Produk Aplikasi -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-2">
                    1. Target Produk Aplikasi
                </label>
                <select name="product_code" id="product_code" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach ($products as $prod)
                        <option value="{{ $prod->value }}" {{ old('product_code') === $prod->value ? 'selected' : '' }}>
                            {{ $prod->label() }} &bull; Prefix Kunci: {{ $prod->prefix() }}-*
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Kunci lisensi otomatis di-generate dengan prefix produk yang Anda pilih.</p>
            </div>

            <!-- Section 2: Data Pelanggan & Entitas Bisnis -->
            <div class="pt-5 border-t border-slate-100 dark:border-slate-800 space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    2. Data Pelanggan & Entitas Bisnis
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pemilik / Customer</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               placeholder="Contoh: H. Ahmad Subardjo"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Bisnis / Toko / Instansi</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" required
                               placeholder="Contoh: Minimarket Barokah / Klinik Sehat"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kode Cabang / Unit</label>
                        <input type="text" name="branch_code" value="{{ old('branch_code', 'BR01') }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Batas Reset HWID Mesin</label>
                        <input type="number" name="max_reset_count" value="{{ old('max_reset_count', 3) }}" min="0" required
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Section 3: Konfigurasi Tier & Masa Aktif -->
            <div class="pt-5 border-t border-slate-100 dark:border-slate-800 space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    3. Konfigurasi Tier & Masa Aktif
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Paket (Tier Entitlement)</label>
                        <select name="plan_type" id="plan_type" onchange="handlePlanChange()"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="STANDARD_LOCAL">STANDARD_LOCAL (Offline Standalone)</option>
                            <option value="PRO_CLOUD_SYNC" selected>PRO_CLOUD_SYNC (Fitur Lengkap + Sync)</option>
                            <option value="ENTERPRISE">ENTERPRISE (Multi-Cabang & API)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Penagihan</label>
                        <select name="billing_type" id="billing_type"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="subscription" selected>Subscription (Langganan 1 Tahun)</option>
                            <option value="lifetime">Lifetime (Permanen)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.licenses.index') }}"
                   class="text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span>Terbitkan & Generate Lisensi</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function handlePlanChange() {
        const plan = document.getElementById('plan_type').value;
        const billing = document.getElementById('billing_type');
        if (plan === 'STANDARD_LOCAL') {
            billing.value = 'lifetime';
        } else {
            billing.value = 'subscription';
        }
    }
</script>
@endsection
