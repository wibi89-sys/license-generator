<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AppProduct;
use App\Enums\LicenseFeature;
use App\Enums\LicensePlan;
use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = License::with(['auditLogs' => fn ($q) => $q->latest()->limit(5)])
            ->latest();

        if ($request->filled('product')) {
            $query->where('product_code', $request->input('product'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('license_key', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhere('business_name', 'like', $search)
                    ->orWhere('pharmacy_name', 'like', $search)
                    ->orWhere('bound_hostname', 'like', $search);
            });
        }

        $licenses = $query->paginate(15)->withQueryString();
        $products = AppProduct::cases();

        return view('admin.licenses.index', compact('licenses', 'products'));
    }

    public function create(): View
    {
        $products = AppProduct::cases();
        $plans = LicensePlan::cases();
        $features = LicenseFeature::cases();

        return view('admin.licenses.create', compact('products', 'plans', 'features'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_code' => ['required', 'string'],
            'customer_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:50'],
            'plan_type' => ['required', 'string'],
            'billing_type' => ['required', 'in:lifetime,subscription'],
            'expires_at' => ['nullable', 'date'],
            'max_reset_count' => ['required', 'integer', 'min:0'],
        ]);

        $product = AppProduct::tryFrom($validated['product_code']) ?? AppProduct::APOTEK_POS;
        $plan = LicensePlan::from($validated['plan_type']);
        $limits = $product->defaultLimits($plan);
        $features = $product->defaultFeatures($plan);

        // Allow feature overrides from request if provided
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($features as $key => $val) {
                if (in_array($key, $request->input('features'), true)) {
                    $features[$key] = true;
                }
            }
        }

        $license = License::create([
            'license_key' => License::generateKey($product),
            'product_code' => $product,
            'customer_name' => $validated['customer_name'],
            'business_name' => $validated['business_name'],
            'pharmacy_name' => $validated['business_name'], // fallback compatibility
            'branch_code' => $validated['branch_code'],
            'plan_type' => $plan,
            'billing_type' => $validated['billing_type'],
            'limits_json' => $limits,
            'features_json' => $features,
            'status' => 'active',
            'max_reset_count' => (int) $validated['max_reset_count'],
            'reset_used' => 0,
            'expires_at' => !empty($validated['expires_at']) ? now()->parse($validated['expires_at']) : ($plan === LicensePlan::STANDARD_LOCAL ? null : now()->addYear()),
            'grace_days' => 14,
            'heartbeat_interval_days' => $plan->defaultHeartbeatIntervalDays(),
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', "Lisensi baru {$license->license_key} ({$product->label()} - {$plan->value}) berhasil dibuat!");
    }

    public function resetHwid(License $license, Request $request): RedirectResponse
    {
        if (! $license->canResetHwid()) {
            return back()->with('error', "Batas reset HWID ({$license->max_reset_count}x) sudah habis untuk lisensi ini.");
        }

        $license->resetHwid($request->ip(), 'Manual HWID reset triggered by administrator');

        return back()->with('success', "HWID lisensi {$license->license_key} berhasil direset! Perangkat klien sekarang dapat melakukan aktivasi mesin baru.");
    }
}
