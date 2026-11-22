<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terbitkan Lisensi Baru — Universal License Server</title>
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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-xl shadow-lg shadow-indigo-500/10 hover:border-indigo-500/60 transition-colors">
                    🔑
                </a>
                <div>
                    <h1 class="text-base font-bold text-white leading-tight">Terbitkan Lisensi Baru</h1>
                    <p class="text-xs text-slate-400">Generate Kunci Kriptografi Ed25519 Sesuai Produk & Tier</p>
                </div>
            </div>
            <a href="{{ route('admin.licenses.index') }}" class="text-xs font-semibold text-slate-300 hover:text-white bg-slate-700/60 hover:bg-slate-700 border border-slate-600/80 px-3.5 py-2 rounded-xl transition-colors">
                ✕ Batal
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-xl">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs">
                    <strong class="font-bold block mb-1">Gagal menyimpan lisensi:</strong>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.licenses.store') }}" class="space-y-6">
                @csrf

                <!-- Pilih Produk Aplikasi -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-indigo-400 mb-2">
                        1. Target Produk Aplikasi
                    </label>
                    <select name="product_code" id="product_code" required
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach ($products as $prod)
                            <option value="{{ $prod->value }}" {{ old('product_code') === $prod->value ? 'selected' : '' }}>
                                {{ $prod->label() }} (Prefix: {{ $prod->prefix() }}-*)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Kunci lisensi akan otomatis diawali prefix produk yang dipilih.</p>
                </div>

                <!-- Informasi Pelanggan & Bisnis -->
                <div class="pt-4 border-t border-slate-700/60">
                    <label class="block text-xs font-bold uppercase tracking-wider text-indigo-400 mb-3">
                        2. Identitas Pelanggan & Bisnis
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Pemilik / Customer</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                placeholder="Contoh: H. Ahmad Subardjo"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Bisnis / Toko / Instansi</label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" required
                                placeholder="Contoh: Toko Maju Jaya / Apotek Sehat"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kode Cabang / Unit</label>
                            <input type="text" name="branch_code" value="{{ old('branch_code', 'BR01') }}" required
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Batas Reset HWID</label>
                            <input type="number" name="max_reset_count" value="{{ old('max_reset_count', 3) }}" min="0" required
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Paket & Skema Lisensi -->
                <div class="pt-4 border-t border-slate-700/60">
                    <label class="block text-xs font-bold uppercase tracking-wider text-indigo-400 mb-3">
                        3. Paket Lisensi & Masa Berlaku
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Paket (Tier Entitlement)</label>
                            <select name="plan_type" id="plan_type" onchange="handlePlanChange()"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                                <option value="STANDARD_LOCAL">STANDARD_LOCAL (Offline Standalone)</option>
                                <option value="PRO_CLOUD_SYNC" selected>PRO_CLOUD_SYNC (Fitur Lengkap + Sync)</option>
                                <option value="ENTERPRISE">ENTERPRISE (Multi-Cabang & API)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tipe Penagihan</label>
                            <select name="billing_type" id="billing_type"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                                <option value="subscription" selected>Subscription (Langganan 1 Tahun)</option>
                                <option value="lifetime">Lifetime (Permanen)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-slate-700/60 flex items-center justify-between">
                    <a href="{{ route('admin.licenses.index') }}" class="text-xs text-slate-400 hover:text-white">
                        ← Kembali ke Daftar Lisensi
                    </a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                        <span>🔑 Terbitkan & Generate Lisensi</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

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
</body>
</html>
