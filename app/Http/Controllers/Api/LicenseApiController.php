<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\Licensing\LicenseSignerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseApiController extends Controller
{
    public function __construct(
        protected LicenseSignerService $signer
    ) {}

    public function activate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'license_key' => ['required', 'string'],
            'hwid' => ['required', 'string', 'size:64'],
            'product_code' => ['nullable', 'string', 'max:50'],
            'hostname' => ['nullable', 'string', 'max:255'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $license = License::where('license_key', $request->input('license_key'))->first();
        if (! $license) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kunci lisensi tidak ditemukan di server Cloud Hub.',
            ], 404);
        }

        // Validate product code compatibility if requested
        $requestedProduct = $request->input('product_code');
        $licenseProductCode = $license->product_code instanceof \BackedEnum ? $license->product_code->value : ((string) ($license->product_code ?? 'APOTEK_POS'));
        if ($requestedProduct && strtoupper($requestedProduct) !== strtoupper($licenseProductCode)) {
            return response()->json([
                'status' => 'error',
                'message' => "Kunci lisensi ini dikhususkan untuk produk {$licenseProductCode}, tidak dapat digunakan pada {$requestedProduct}.",
            ], 403);
        }

        $hwid = $request->input('hwid');
        $ip = $request->ip();
        $appVersion = $request->input('app_version', '1.0.0');

        if ($license->status === 'revoked') {
            $license->auditLogs()->create([
                'action' => 'rejected_revoked',
                'hwid' => $hwid,
                'ip_address' => $ip,
                'app_version' => $appVersion,
                'notes' => 'Aktivasi ditolak: lisensi telah dicabut (revoked).',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Lisensi ini telah dicabut oleh administrator.',
            ], 403);
        }

        if ($license->status === 'suspended') {
            return response()->json([
                'status' => 'error',
                'message' => 'Lisensi ini sedang ditangguhkan (suspended).',
            ], 403);
        }

        if ($license->isExpired()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Masa berlaku lisensi ini telah kedaluwarsa.',
            ], 403);
        }

        // Cek HWID binding
        if ($license->bound_hwid !== null && $license->bound_hwid !== $hwid) {
            $license->auditLogs()->create([
                'action' => 'rejected_hwid_mismatch',
                'hwid' => $hwid,
                'ip_address' => $ip,
                'app_version' => $appVersion,
                'notes' => sprintf('Aktivasi ditolak: HWID mismatch. Terikat ke %s, dicoba dari %s', $license->bound_hwid, $hwid),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'HWID mesin tidak cocok dengan lisensi terdaftar. Silakan reset HWID melalui portal admin Cloud Hub.',
                'bound_hostname' => $license->bound_hostname,
            ], 422);
        }

        // First activation: bind HWID
        if ($license->bound_hwid === null) {
            $license->bound_hwid = $hwid;
            $license->bound_hostname = $request->input('hostname', gethostname());
            $license->activated_at = now();
            $license->save();

            $license->auditLogs()->create([
                'action' => 'activated',
                'hwid' => $hwid,
                'ip_address' => $ip,
                'app_version' => $appVersion,
                'notes' => 'Lisensi berhasil diaktivasi pertama kali pada mesin ini.',
            ]);
        } else {
            $license->auditLogs()->create([
                'action' => 'refreshed',
                'hwid' => $hwid,
                'ip_address' => $ip,
                'app_version' => $appVersion,
                'notes' => 'Lisensi direfresh/re-aktivasi pada mesin yang sama.',
            ]);
        }

        $payload = $this->signer->buildPayload($license, $hwid);
        $certificate = $this->signer->signPayload($payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Lisensi berhasil diaktivasi.',
            'certificate' => $certificate,
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'license_key' => ['required', 'string'],
            'hwid' => ['required', 'string', 'size:64'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $license = License::where('license_key', $request->input('license_key'))->first();
        if (! $license) {
            return response()->json(['status' => 'error', 'message' => 'Lisensi tidak ditemukan.'], 404);
        }

        $hwid = $request->input('hwid');
        if ($license->bound_hwid !== $hwid) {
            return response()->json(['status' => 'error', 'message' => 'HWID mismatch.'], 422);
        }

        $payload = $this->signer->buildPayload($license, $hwid);
        $certificate = $this->signer->signPayload($payload);

        $license->auditLogs()->create([
            'action' => 'refreshed',
            'hwid' => $hwid,
            'ip_address' => $request->ip(),
            'app_version' => $request->input('app_version'),
            'notes' => 'Lisensi sertifikat diperbarui secara online.',
        ]);

        return response()->json([
            'status' => 'success',
            'certificate' => $certificate,
        ]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'license_key' => ['required', 'string'],
            'hwid' => ['required', 'string', 'size:64'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error'], 422);
        }

        $license = License::where('license_key', $request->input('license_key'))->first();
        if (! $license) {
            return response()->json(['status' => 'error', 'message' => 'License not found'], 404);
        }

        $license->last_heartbeat_at = now();
        $license->save();

        $license->auditLogs()->create([
            'action' => 'heartbeat',
            'hwid' => $request->input('hwid'),
            'ip_address' => $request->ip(),
            'app_version' => $request->input('app_version'),
            'notes' => 'Heartbeat ping received.',
        ]);

        return response()->json([
            'status' => 'success',
            'license_status' => $license->status,
            'plan_type' => $license->plan_type->value,
            'is_expired' => $license->isExpired(),
        ]);
    }
}
