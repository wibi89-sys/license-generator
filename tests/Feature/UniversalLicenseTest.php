<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AppProduct;
use App\Models\License;
use Database\Seeders\LicenseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniversalLicenseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LicenseSeeder::class);
    }

    public function test_key_generator_uses_proper_product_prefixes(): void
    {
        $aptKey = License::generateKey(AppProduct::APOTEK_POS);
        $wrgKey = License::generateKey(AppProduct::RETAIL_POS);
        $absKey = License::generateKey(AppProduct::ATTENDANCE_APP);
        $klnKey = License::generateKey(AppProduct::CLINIC_APP);

        $this->assertStringStartsWith('APT-', $aptKey);
        $this->assertStringStartsWith('WRG-', $wrgKey);
        $this->assertStringStartsWith('ABS-', $absKey);
        $this->assertStringStartsWith('KLN-', $klnKey);
    }

    public function test_apotek_pos_activation_succeeds(): void
    {
        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => 'APT-DEMO-2026-LIVE',
            'hwid' => str_repeat('a', 64),
            'product_code' => 'APOTEK_POS',
            'hostname' => 'KASIR-01',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('success', $data['status']);
        $payload = json_decode(base64_decode($data['certificate']['payload_b64']), true);
        $this->assertEquals('APT-DEMO-2026-LIVE', $payload['license_key']);
        $this->assertEquals('APOTEK_POS', $payload['product_code']);
        $this->assertTrue($payload['features']['fingerprint_auth']);
    }

    public function test_retail_warung_activation_succeeds(): void
    {
        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => 'WRG-DEMO-2026-LIVE',
            'hwid' => str_repeat('b', 64),
            'product_code' => 'RETAIL_POS',
            'hostname' => 'WARUNG-KASIR',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('success', $data['status']);
        $payload = json_decode(base64_decode($data['certificate']['payload_b64']), true);
        $this->assertEquals('WRG-DEMO-2026-LIVE', $payload['license_key']);
        $this->assertEquals('RETAIL_POS', $payload['product_code']);
        $this->assertTrue($payload['features']['barcode_scanner']);
    }

    public function test_attendance_app_activation_succeeds(): void
    {
        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => 'ABS-DEMO-2026-LIVE',
            'hwid' => str_repeat('c', 64),
            'product_code' => 'ATTENDANCE_APP',
            'hostname' => 'ABSENSI-LOBBY',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('success', $data['status']);
        $payload = json_decode(base64_decode($data['certificate']['payload_b64']), true);
        $this->assertEquals('ABS-DEMO-2026-LIVE', $payload['license_key']);
        $this->assertEquals('ATTENDANCE_APP', $payload['product_code']);
        $this->assertTrue($payload['features']['u_are_u_fingerprint']);
    }

    public function test_rejects_cross_product_activation(): void
    {
        // Try to activate Warung license on Apotek POS
        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => 'WRG-DEMO-2026-LIVE',
            'hwid' => str_repeat('d', 64),
            'product_code' => 'APOTEK_POS', // Mismatch!
            'hostname' => 'KASIR-APOTEK',
        ]);

        $response->assertStatus(403);
        $this->assertStringContainsString('dikhususkan untuk produk RETAIL_POS', $response->json('message'));
    }
}
