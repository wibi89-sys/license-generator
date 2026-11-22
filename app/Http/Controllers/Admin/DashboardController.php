<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CloudInventoryBatch;
use App\Models\CloudTransaction;
use App\Models\License;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // 1. Turnover & Gross Profit Aggregations
        $todayTransactions = CloudTransaction::whereDate('transaction_date', $today)->where('status', 'completed')->get();
        $monthTransactions = CloudTransaction::where('transaction_date', '>=', $thisMonth)->where('status', 'completed')->get();

        $todayOmzet = $todayTransactions->sum(fn($t) => (float) $t->grand_total);
        $monthOmzet = $monthTransactions->sum(fn($t) => (float) $t->grand_total);
        $monthGrossProfit = $monthTransactions->sum(fn($t) => (float) $t->gross_profit);

        // 2. Branch Comparisons
        $branchTurnover = CloudTransaction::select('branch_code', DB::raw('SUM(grand_total) as total_turnover'), DB::raw('COUNT(*) as total_transactions'))
            ->where('status', 'completed')
            ->groupBy('branch_code')
            ->get();

        // 3. Expiry Alert (Near ED < 90 Days)
        $nearExpiryBatches = CloudInventoryBatch::where('expired_date', '<=', Carbon::now()->addDays(90))
            ->where('stock_qty', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->take(15)
            ->get();

        // 4. Low Stock Alert (< 10 units)
        $lowStockBatches = CloudInventoryBatch::where('stock_qty', '<=', 10)
            ->where('stock_qty', '>', 0)
            ->orderBy('stock_qty', 'asc')
            ->take(15)
            ->get();

        // 5. Total Active Licenses
        $activeLicensesCount = License::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'todayOmzet',
            'monthOmzet',
            'monthGrossProfit',
            'branchTurnover',
            'nearExpiryBatches',
            'lowStockBatches',
            'activeLicensesCount'
        ));
    }
}
