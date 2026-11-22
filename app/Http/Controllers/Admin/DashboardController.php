<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AppProduct;
use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseAuditLog;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. License Overview Metrics
        $totalLicenses = License::count();
        $activeLicenses = License::where('status', 'active')->count();
        $boundLicenses = License::whereNotNull('bound_hwid')->count();
        $unboundLicenses = License::whereNull('bound_hwid')->count();

        // Expired or Expiring Soon (< 30 days)
        $expiringSoonCount = License::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now()->addDays(30))
            ->count();

        // 2. Product Distribution Breakdown
        $productStats = [];
        foreach (AppProduct::cases() as $product) {
            $count = License::where('product_code', $product)->count();
            $activeCount = License::where('product_code', $product)->where('status', 'active')->count();
            $boundCount = License::where('product_code', $product)->whereNotNull('bound_hwid')->count();

            $productStats[$product->value] = [
                'enum' => $product,
                'label' => $product->label(),
                'prefix' => $product->prefix(),
                'total' => $count,
                'active' => $activeCount,
                'bound' => $boundCount,
            ];
        }

        // 3. Recent Licenses
        $recentLicenses = License::latest()
            ->take(8)
            ->get();

        // 4. Real-time Audit / Activity Logs
        $recentAuditLogs = LicenseAuditLog::with('license')
            ->latest('id')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalLicenses',
            'activeLicenses',
            'boundLicenses',
            'unboundLicenses',
            'expiringSoonCount',
            'productStats',
            'recentLicenses',
            'recentAuditLogs'
        ));
    }
}
