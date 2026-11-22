<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\LicenseFeature;
use App\Enums\LicensePlan;
use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    public function index(): View
    {
        $licenses = License::with(['auditLogs' => fn ($q) => $q->latest()->limit(5)])
            ->latest()
            ->paginate(15);

        return view('admin.licenses.index', compact('licenses'));
    }

    public function create(): View
    {
        $plans = LicensePlan::cases();
        $features = LicenseFeature::cases();

        return view('admin.licenses.create', compact('plans', 'features'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'pharmacy_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:50'],
            'plan_type' => ['required', 'string'],
            'billing_type' => ['required', 'in:lifetime,subscription'],
            'expires_at' => ['nullable', 'date'],
            'max_reset_count' => ['required', 'integer', 'min:0'],
        ]);

        $plan = LicensePlan::from($validated['plan_type']);
        $limits = $plan->defaultLimits();
        $features = $plan->defaultFeatures();

        // Allow feature overrides from request if provided
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach (LicenseFeature::cases() as $feature) {
                $features[$feature->value] = in_array($feature->value, $request->input('features'), true);
            }
        }

        $license = License::create([
            'license_key' => License::generateKey(),
            'customer_name' => $validated['customer_name'],
            'pharmacy_name' => $validated['pharmacy_name'],
            'branch_code' => $validated['branch_code'],
            'plan_type' => $plan,
            'billing_type' => $validated['billing_type'],
            'limits_json' => $limits,
            'features_json' => $features,
            'status' => 'active',
            'max_reset_count' => (int) $validated['max_reset_count'],
            'reset_used' => 0,
            'expires_at' => $validated['expires_at'] ? now()->parse($validated['expires_at']) : ($plan === LicensePlan::STANDARD_LOCAL ? null : now()->addYear()),
            'grace_days' => 14,
            'heartbeat_interval_days' => $plan->defaultHeartbeatIntervalDays(),
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', "Lisensi baru {$license->license_key} ({$plan->value}) berhasil dibuat!");
    }

    public function resetHwid(License $license, Request $request): RedirectResponse
    {
        if (! $license->canResetHwid()) {
            return back()->with('error', "Batas reset HWID ({$license->max_reset_count}x) sudah habis untuk lisensi ini.");
        }

        $license->resetHwid($request->ip(), 'Manual HWID reset triggered by administrator');

        return back()->with('success', "HWID lisensi {$license->license_key} berhasil direset! Terminal kasir sekarang dapat mengaktivasi mesin baru.");
    }
}
