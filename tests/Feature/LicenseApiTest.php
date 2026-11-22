<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\LicensePlan;
use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure keys exist in test environment
        $signer = app(\App\Services\Licensing\LicenseSignerService::class);
        $keys = $signer->generateKeypair();
        config([
            'license.private_key' => $keys['private_key'],
            'license.public_key' => $keys['public_key'],
        ]);
    }

    public function test_can_activate_license_first_time_and_binds_hwid(): void
    {
        $license = License::create([
            'license_key' => 'APT-TEST-1111-2222',
            'customer_name' => 'Dr. Test',
            'pharmacy_name' => 'Apotek Uji',
            'branch_code' => 'BR01',
            'plan_type' => LicensePlan::STANDARD_LOCAL,
            'billing_type' => 'lifetime',
            'limits_json' => LicensePlan::STANDARD_LOCAL->defaultLimits(),
            'features_json' => LicensePlan::STANDARD_LOCAL->defaultFeatures(),
            'status' => 'active',
            'max_reset_count' => 2,
            'reset_used' => 0,
            'grace_days' => 14,
            'heartbeat_interval_days' => 0,
        ]);

        $hwid = str_repeat('a', 64);

        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => $license->license_key,
            'hwid' => $hwid,
            'hostname' => 'KASIR-01',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'certificate' => ['version', 'algorithm', 'payload_b64', 'signature_b64'],
            ]);

        $this->assertDatabaseHas('licenses', [
            'id' => $license->id,
            'bound_hwid' => $hwid,
            'bound_hostname' => 'KASIR-01',
        ]);

        $this->assertDatabaseHas('license_audit_logs', [
            'license_id' => $license->id,
            'action' => 'activated',
            'hwid' => $hwid,
        ]);
    }

    public function test_rejects_activation_when_hwid_mismatches_bound_hwid(): void
    {
        $license = License::create([
            'license_key' => 'APT-BOUND-3333-4444',
            'customer_name' => 'Dr. Test',
            'pharmacy_name' => 'Apotek Uji',
            'branch_code' => 'BR01',
            'plan_type' => LicensePlan::PRO_CLOUD_SYNC,
            'billing_type' => 'subscription',
            'limits_json' => LicensePlan::PRO_CLOUD_SYNC->defaultLimits(),
            'features_json' => LicensePlan::PRO_CLOUD_SYNC->defaultFeatures(),
            'status' => 'active',
            'bound_hwid' => str_repeat('1', 64),
            'bound_hostname' => 'PC-ORIGINAL',
            'max_reset_count' => 2,
            'reset_used' => 0,
        ]);

        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => $license->license_key,
            'hwid' => str_repeat('2', 64), // Different HWID
            'hostname' => 'PC-CLONE',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseHas('license_audit_logs', [
            'license_id' => $license->id,
            'action' => 'rejected_hwid_mismatch',
        ]);
    }

    public function test_can_reset_hwid_and_activate_on_new_machine(): void
    {
        $license = License::create([
            'license_key' => 'APT-RESET-5555-6666',
            'customer_name' => 'Dr. Test',
            'pharmacy_name' => 'Apotek Uji',
            'branch_code' => 'BR01',
            'plan_type' => LicensePlan::STANDARD_LOCAL,
            'billing_type' => 'lifetime',
            'limits_json' => LicensePlan::STANDARD_LOCAL->defaultLimits(),
            'features_json' => LicensePlan::STANDARD_LOCAL->defaultFeatures(),
            'status' => 'active',
            'bound_hwid' => str_repeat('1', 64),
            'max_reset_count' => 2,
            'reset_used' => 0,
        ]);

        $this->assertTrue($license->resetHwid('127.0.0.1', 'Reset test'));
        $this->assertEquals(1, $license->fresh()->reset_used);
        $this->assertNull($license->fresh()->bound_hwid);

        // Now activate with new HWID
        $newHwid = str_repeat('3', 64);
        $response = $this->postJson('/api/v1/license/activate', [
            'license_key' => $license->license_key,
            'hwid' => $newHwid,
            'hostname' => 'NEW-PC',
        ]);

        $response->assertStatus(200);
        $this->assertEquals($newHwid, $license->fresh()->bound_hwid);
    }
}
