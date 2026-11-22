<?php

declare(strict_types=1);

namespace App\Services\Licensing;

use App\Models\License;
use RuntimeException;

class LicenseSignerService
{
    /**
     * Generate a new Ed25519 keypair encoded in Base64.
     */
    public function generateKeypair(): array
    {
        $keypair = sodium_crypto_sign_keypair();
        $secretKey = sodium_crypto_sign_secretkey($keypair);
        $publicKey = sodium_crypto_sign_publickey($keypair);

        return [
            'private_key' => base64_encode($secretKey),
            'public_key' => base64_encode($publicKey),
        ];
    }

    /**
     * Build the raw entitlement payload dictionary for a license.
     */
    public function buildPayload(License $license, string $hwid): array
    {
        $productCode = $license->product_code instanceof \BackedEnum ? $license->product_code->value : ((string) ($license->product_code ?? 'APOTEK_POS'));

        return [
            'schema_version' => 1,
            'product_code' => $productCode,
            'license_key' => $license->license_key,
            'customer_name' => $license->customer_name,
            'pharmacy_name' => $license->pharmacy_name ?? $license->business_name,
            'business_name' => $license->business_name ?? $license->pharmacy_name,
            'branch_code' => $license->branch_code,
            'hwid' => $hwid,
            'plan' => $license->plan_type instanceof \BackedEnum ? $license->plan_type->value : (string) $license->plan_type,
            'billing_type' => $license->billing_type,
            'issued_at' => now()->timestamp,
            'expires_at' => $license->expires_at?->timestamp,
            'grace_days' => $license->grace_days,
            'heartbeat_interval_days' => $license->heartbeat_interval_days,
            'limits' => $license->limits_json ?? [],
            'features' => $license->features_json ?? [],
        ];
    }

    /**
     * Sign the payload and return the detached signature structure.
     */
    public function signPayload(array $payload, ?string $privateKeyB64 = null): array
    {
        $keyB64 = $privateKeyB64 ?? config('license.private_key');
        if (empty($keyB64)) {
            throw new RuntimeException('LICENSE_PRIVATE_KEY is not configured on Cloud Hub.');
        }

        $privateKey = base64_decode($keyB64, true);
        if ($privateKey === false || strlen($privateKey) !== SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
            throw new RuntimeException('Invalid Ed25519 private key.');
        }

        $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($payloadJson === false) {
            throw new RuntimeException('Failed to JSON encode payload.');
        }

        $payloadB64 = base64_encode($payloadJson);
        $signature = sodium_crypto_sign_detached($payloadB64, $privateKey);

        return [
            'version' => 1,
            'algorithm' => 'Ed25519',
            'payload_b64' => $payloadB64,
            'signature_b64' => base64_encode($signature),
        ];
    }
}
