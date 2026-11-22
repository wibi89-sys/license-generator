<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Hub - Dashboard Monitoring Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen">
    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl">☁️</span>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight">Apotek Cloud Hub</h1>
                    <p class="text-xs text-slate-500">Pusat Monitoring Multi-Cabang & Lisensi Apotek</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/licenses" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg transition-colors">
                    🔑 Kelola Lisensi
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    🟢 Online
                </span>
                <span class="text-xs text-slate-500 hidden sm:inline border-l border-slate-200 pl-3">
                    Halo, <strong class="text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Top Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase">Omzet Hari Ini</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($todayOmzet, 0, ',', '.') }}</p>
                <span class="text-xs text-slate-400">Total akumulasi semua cabang</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase">Omzet Bulan Ini</span>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($monthOmzet, 0, ',', '.') }}</p>
                <span class="text-xs text-slate-400">Periode berjalan</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase">Laba Kotor (Gross Profit)</span>
                <p class="text-2xl font-bold text-indigo-600 mt-1">Rp {{ number_format($monthGrossProfit, 0, ',', '.') }}</p>
                <span class="text-xs text-slate-400">Berdasarkan HPP snapshot FEFO</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase">Cabang / Lisensi Aktif</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $activeLicensesCount }} Cabang</p>
                <span class="text-xs text-emerald-600 font-medium">Terhubung secara aman</span>
            </div>
        </div>

        <!-- Branch Turnover Comparison -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Perbandingan Kinerja Omzet Per Cabang</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-700 font-semibold">
                        <tr>
                            <th class="px-4 py-2 text-left">Kode Cabang</th>
                            <th class="px-4 py-2 text-right">Total Transaksi</th>
                            <th class="px-4 py-2 text-right">Total Omzet Penjualan</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($branchTurnover as $branch)
                            <tr>
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $branch->branch_code }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($branch->total_transactions, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">Rp {{ number_format((float) $branch->total_turnover, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif Beroperasi
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                                    Belum ada transaksi tersinkronisasi dari cabang lokal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2 Column Alerts: Near Expiry & Low Stock -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Expiry Warning Alert -->
            <div class="bg-white rounded-xl border border-amber-200 shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>⏳</span> Peringatan Obat Mendekati ED (&lt; 90 Hari)
                    </h3>
                    <span class="text-xs bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full">
                        {{ $nearExpiryBatches->count() }} Batch
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="bg-amber-50 text-amber-900 font-semibold">
                            <tr>
                                <th class="px-3 py-2 text-left">Cabang</th>
                                <th class="px-3 py-2 text-left">Nama Obat</th>
                                <th class="px-3 py-2 text-left">No Batch</th>
                                <th class="px-3 py-2 text-center">Tgl Expired</th>
                                <th class="px-3 py-2 text-right">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($nearExpiryBatches as $batch)
                                <tr>
                                    <td class="px-3 py-2 font-mono">{{ $batch->branch_code }}</td>
                                    <td class="px-3 py-2 font-medium">{{ $batch->product_name }}</td>
                                    <td class="px-3 py-2 font-mono">{{ $batch->batch_number }}</td>
                                    <td class="px-3 py-2 text-center font-bold text-amber-700">{{ $batch->expired_date->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2 text-right font-bold">{{ $batch->stock_qty }} {{ $batch->unit_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-center text-slate-400">
                                        Tidak ada obat yang mendekati tanggal kedaluwarsa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Warning Alert -->
            <div class="bg-white rounded-xl border border-rose-200 shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>📉</span> Peringatan Stok Kritis (&le; 10 Unit)
                    </h3>
                    <span class="text-xs bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded-full">
                        {{ $lowStockBatches->count() }} Batch
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="bg-rose-50 text-rose-900 font-semibold">
                            <tr>
                                <th class="px-3 py-2 text-left">Cabang</th>
                                <th class="px-3 py-2 text-left">Nama Obat</th>
                                <th class="px-3 py-2 text-left">No Batch</th>
                                <th class="px-3 py-2 text-right">Stok Kritis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($lowStockBatches as $batch)
                                <tr>
                                    <td class="px-3 py-2 font-mono">{{ $batch->branch_code }}</td>
                                    <td class="px-3 py-2 font-medium">{{ $batch->product_name }}</td>
                                    <td class="px-3 py-2 font-mono">{{ $batch->batch_number }}</td>
                                    <td class="px-3 py-2 text-right font-bold text-rose-600">{{ $batch->stock_qty }} {{ $batch->unit_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-4 text-center text-slate-400">
                                        Seluruh stok obat berada dalam batas aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
