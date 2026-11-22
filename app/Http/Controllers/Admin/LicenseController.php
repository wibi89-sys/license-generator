<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AppProduct;
use App\Enums\LicenseFeature;
use App\Enums\LicensePlan;
use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\Licensing\LicenseSignerService;
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

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('license_key', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhere('business_name', 'like', $search)
                    ->orWhere('pharmacy_name', 'like', $search)
                    ->orWhere('bound_hostname', 'like', $search)
                    ->orWhere('bound_hwid', 'like', $search);
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
                $features[$key] = in_array($key, $request->input('features'), true);
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

        $license->auditLogs()->create([
            'action' => 'created',
            'ip_address' => $request->ip(),
            'notes' => "Lisensi diterbitkan untuk {$license->business_name} ({$license->customer_name})",
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', "Lisensi baru {$license->license_key} ({$product->label()} - {$plan->value}) berhasil diterbitkan!");
    }

    public function show(License $license): View
    {
        $license->load(['auditLogs' => fn ($q) => $q->latest()->limit(50)]);
        $product = $license->product_code instanceof AppProduct
            ? $license->product_code
            : (AppProduct::tryFrom((string) $license->product_code) ?? AppProduct::APOTEK_POS);

        return view('admin.licenses.show', compact('license', 'product'));
    }

    public function edit(License $license): View
    {
        $products = AppProduct::cases();
        $plans = LicensePlan::cases();
        $features = LicenseFeature::cases();
        $product = $license->product_code instanceof AppProduct
            ? $license->product_code
            : (AppProduct::tryFrom((string) $license->product_code) ?? AppProduct::APOTEK_POS);

        return view('admin.licenses.edit', compact('license', 'products', 'plans', 'features', 'product'));
    }

    public function update(Request $request, License $license): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'branch_code' => ['required', 'string', 'max:50'],
            'plan_type' => ['required', 'string'],
            'billing_type' => ['required', 'in:lifetime,subscription'],
            'status' => ['required', 'in:active,suspended,revoked,expired'],
            'max_reset_count' => ['required', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $plan = LicensePlan::from($validated['plan_type']);
        $product = $license->product_code instanceof AppProduct
            ? $license->product_code
            : (AppProduct::tryFrom((string) $license->product_code) ?? AppProduct::APOTEK_POS);

        $features = $license->features_json ?? $product->defaultFeatures($plan);
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($features as $key => $val) {
                $features[$key] = in_array($key, $request->input('features'), true);
            }
        }

        $limits = $license->limits_json ?? $product->defaultLimits($plan);
        if ($request->has('limits') && is_array($request->input('limits'))) {
            foreach ($request->input('limits') as $limitKey => $limitVal) {
                if (isset($limits[$limitKey])) {
                    $limits[$limitKey] = (int) $limitVal;
                }
            }
        }

        $license->update([
            'customer_name' => $validated['customer_name'],
            'business_name' => $validated['business_name'],
            'pharmacy_name' => $validated['business_name'],
            'branch_code' => $validated['branch_code'],
            'plan_type' => $plan,
            'billing_type' => $validated['billing_type'],
            'status' => $validated['status'],
            'max_reset_count' => (int) $validated['max_reset_count'],
            'expires_at' => !empty($validated['expires_at']) ? now()->parse($validated['expires_at']) : null,
            'features_json' => $features,
            'limits_json' => $limits,
        ]);

        $license->auditLogs()->create([
            'action' => 'admin_updated',
            'ip_address' => $request->ip(),
            'notes' => "Perubahan data lisensi oleh admin. Status: {$license->status}, Plan: {$plan->value}",
        ]);

        return redirect()->route('admin.licenses.show', $license)
            ->with('success', "Data lisensi {$license->license_key} berhasil diperbarui!");
    }

    public function destroy(License $license): RedirectResponse
    {
        $key = $license->license_key;
        $name = $license->business_name ?? $license->customer_name;
        $license->delete();

        return redirect()->route('admin.licenses.index')
            ->with('success', "Lisensi {$key} ({$name}) berhasil dihapus permanen dari sistem.");
    }

    public function resetHwid(License $license, Request $request): RedirectResponse
    {
        if (! $license->canResetHwid()) {
            return back()->with('error', "Batas reset HWID ({$license->max_reset_count}x) sudah habis untuk lisensi ini.");
        }

        $license->resetHwid($request->ip(), 'Manual HWID reset triggered by administrator');

        return back()->with('success', "HWID lisensi {$license->license_key} berhasil direset! Perangkat klien sekarang dapat melakukan aktivasi mesin baru.");
    }

    public function toggleStatus(License $license, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,suspended,revoked'],
        ]);

        $prevStatus = $license->status;
        $license->status = $validated['status'];
        $license->save();

        $license->auditLogs()->create([
            'action' => 'status_changed',
            'ip_address' => $request->ip(),
            'notes' => "Status lisensi diubah dari {$prevStatus} menjadi {$license->status} oleh admin",
        ]);

        return back()->with('success', "Status lisensi {$license->license_key} berhasil diubah menjadi " . strtoupper($license->status));
    }

    public function downloadLic(Request $request, License $license, LicenseSignerService $signer)
    {
        $hwid = $request->input('hwid', $license->bound_hwid);

        if (empty($hwid)) {
            return back()->with('error', 'Lisensi belum terikat pada HWID komputer mana pun. Silakan aktifkan online terlebih dahulu atau tentukan HWID tujuan.');
        }

        // If hwid is provided for unbound license, bind it
        if (empty($license->bound_hwid) && !empty($hwid)) {
            $license->update([
                'bound_hwid' => $hwid,
                'bound_at' => now(),
            ]);
        }

        $payload = $signer->buildPayload($license, (string) $hwid);
        $cert = $signer->signPayload($payload);

        $json = json_encode($cert, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = "activation_{$license->license_key}.lic";

        $license->auditLogs()->create([
            'action' => 'downloaded_lic',
            'ip_address' => $request->ip(),
            'notes' => "File sertifikat lisensi offline ({$filename}) diunduh untuk HWID {$hwid}",
        ]);

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
