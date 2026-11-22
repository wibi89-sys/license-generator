@extends('layouts.app')

@section('title', 'Edit Lisensi ' . $license->license_key . ' — Universal License Server')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('admin.licenses.show', $license) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    &larr; Batal & Kembali ke Detail
                </a>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                Edit Lisensi <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ $license->license_key }}</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Perbarui informasi pelanggan, status, limit aktivasi, dan masa berlaku lisensi.
            </p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs">
        
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900 text-rose-800 dark:text-rose-300 text-xs">
                <p class="font-semibold mb-1">Harap periksa kembali isian formulir:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.licenses.update', $license) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Bisnis & Pelanggan -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">
                    1. Informasi Bisnis & Pelanggan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="business_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Bisnis / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name"
                               value="{{ old('business_name', $license->business_name ?? $license->pharmacy_name) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label for="customer_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Pemilik / Penanggung Jawab <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name"
                               value="{{ old('customer_name', $license->customer_name) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label for="branch_code" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kode Cabang / Outlet <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="branch_code" name="branch_code"
                               value="{{ old('branch_code', $license->branch_code) }}" required
                               class="w-full font-mono bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Status Lisensi <span class="text-rose-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="active" {{ old('status', $license->status) === 'active' ? 'selected' : '' }}>Aktif (Active)</option>
                            <option value="suspended" {{ old('status', $license->status) === 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                            <option value="revoked" {{ old('status', $license->status) === 'revoked' ? 'selected' : '' }}>Dicabut (Revoked)</option>
                            <option value="expired" {{ old('status', $license->status) === 'expired' ? 'selected' : '' }}>Kedaluwarsa (Expired)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Paket & Ketentuan Tagihan -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">
                    2. Paket Lisensi & Kuota Aktivasi
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="plan_type" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tier Paket <span class="text-rose-500">*</span>
                        </label>
                        <select id="plan_type" name="plan_type" required
                                class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            @foreach ($plans as $p)
                                <option value="{{ $p->value }}" {{ old('plan_type', $license->plan_type instanceof \BackedEnum ? $license->plan_type->value : $license->plan_type) === $p->value ? 'selected' : '' }}>
                                    {{ $p->value }} ({{ $p->defaultHeartbeatIntervalDays() }} hari heartbeat)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="billing_type" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Model Tagihan <span class="text-rose-500">*</span>
                        </label>
                        <select id="billing_type" name="billing_type" required
                                class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="lifetime" {{ old('billing_type', $license->billing_type) === 'lifetime' ? 'selected' : '' }}>Lifetime (Sekali Beli / Seumur Hidup)</option>
                            <option value="subscription" {{ old('billing_type', $license->billing_type) === 'subscription' ? 'selected' : '' }}>Subscription (Langganan Berkala)</option>
                        </select>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Kedaluwarsa (Kosongkan jika Lifetime)
                        </label>
                        <input type="date" id="expires_at" name="expires_at"
                               value="{{ old('expires_at', $license->expires_at ? $license->expires_at->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div>
                        <label for="max_reset_count" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Batas Maksimal Reset HWID <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="max_reset_count" name="max_reset_count" min="0" max="99"
                               value="{{ old('max_reset_count', $license->max_reset_count) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <span class="text-[11px] text-slate-400 mt-1 block">Saat ini telah digunakan: {{ $license->reset_used }}x</span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Modul Fitur -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
                    3. Modul & Fitur Diizinkan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($features as $feat)
                        @php
                            $isEnabled = false;
                            if (is_array($license->features_json) && isset($license->features_json[$feat->value])) {
                                $isEnabled = (bool) $license->features_json[$feat->value];
                            }
                        @endphp
                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer hover:bg-slate-100/50 dark:hover:bg-slate-800/80 transition-colors">
                            <input type="checkbox" name="features[]" value="{{ $feat->value }}"
                                   {{ $isEnabled ? 'checked' : '' }}
                                   class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-semibold text-slate-900 dark:text-slate-100 block">{{ $feat->label() }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $feat->value }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.licenses.show', $license) }}"
                   class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-600/20 transition-all">
                    Simpan Perubahan Lisensi
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
