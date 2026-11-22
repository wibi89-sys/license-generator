<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CloudTransaction;
use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CloudSyncApiTest extends TestCase
{
    use RefreshDatabase;

    protected License $proLicense;
    protected License $standardLicense;

    protected function setUp(): void
    {
        parent::setUp();

        $this->standardLicense = License::create([
            'license_key' => 'APT-STD-0001',
            'customer_name' => 'Apotek Standard',
            'pharmacy_name' => 'Apotek Mandiri',
            'branch_code' => 'BR01',
            'plan_type' => 'STANDARD_LOCAL',
            'billing_type' => 'lifetime',
            'limits_json' => ['max_terminals' => 2],
            'features_json' => ['cloud_sync_push' => false, 'cloud_sync_pull' => false],
            'status' => 'active',
        ]);

        $this->proLicense = License::create([
            'license_key' => 'APT-PRO-0001',
            'customer_name' => 'Apotek Pro',
            'pharmacy_name' => 'Apotek Sentosa',
            'branch_code' => 'BR02',
            'plan_type' => 'PRO_CLOUD_SYNC',
            'billing_type' => 'subscription',
            'limits_json' => ['max_terminals' => 5],
            'features_json' => ['cloud_sync_push' => true, 'cloud_sync_pull' => false],
            'status' => 'active',
        ]);
    }

    public function test_sync_push_forbidden_without_valid_license(): void
    {
        $response = $this->postJson('/api/v1/sync/push', [
            'license_key' => 'APT-INVALID-KEY',
            'branch_code' => 'BR01',
        ]);

        $response->assertStatus(403);
    }

    public function test_sync_push_forbidden_for_standard_local_tier(): void
    {
        $response = $this->postJson('/api/v1/sync/push', [
            'license_key' => $this->standardLicense->license_key,
            'branch_code' => 'BR01',
            'transactions' => [],
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('status', 'error');
    }

    public function test_sync_push_success_and_idempotent_by_ulid(): void
    {
        $fixedUlid = '01m80sampletxulid1234567890';
        $payload = [
            'license_key' => $this->proLicense->license_key,
            'branch_code' => 'BR02',
            'transactions' => [
                [
                    'id' => $fixedUlid,
                    'invoice_no' => 'INV-BR02-20261122-0001',
                    'cashier_name' => 'Kasir Rina',
                    'type' => 'otc',
                    'subtotal' => '50000.00',
                    'grand_total' => '50000.00',
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'created_at' => now()->toIso8601String(),
                    'items' => [
                        [
                            'id' => '01m80sampleitem1234567890',
                            'product_name' => 'Amoxicillin 500mg',
                            'batch_number' => 'AMX-01',
                            'qty_input' => 10,
                            'unit_price' => '5000.00',
                            'hpp_base_snapshot' => '3000.00',
                            'subtotal' => '50000.00',
                        ],
                    ],
                ],
            ],
            'shifts' => [
                [
                    'id' => '01m80sampleshift123456789',
                    'terminal_id' => 'POS01',
                    'cashier_name' => 'Kasir Rina',
                    'opening_cash' => '100000.00',
                    'actual_cash' => '150000.00',
                    'status' => 'closed',
                ],
            ],
        ];

        // First push
        $response1 = $this->postJson('/api/v1/sync/push', $payload);
        $response1->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('synced_counts.transactions', 1);

        $this->assertEquals(1, CloudTransaction::count());
        $tx = CloudTransaction::find($fixedUlid);
        $this->assertNotNull($tx);
        $this->assertEquals('30000.00', number_format((float) $tx->hpp_total, 2, '.', ''));
        $this->assertEquals('20000.00', number_format((float) $tx->gross_profit, 2, '.', ''));

        // Second push (Simulate offline retry / network duplicate)
        $response2 = $this->postJson('/api/v1/sync/push', $payload);
        $response2->assertStatus(200);

        // MUST remain exactly 1 row (Idempotency guaranteed)
        $this->assertEquals(1, CloudTransaction::count());
    }

    public function test_dashboard_renders_metrics(): void
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200)
            ->assertSee('Apotek Cloud Hub')
            ->assertSee('Perbandingan Kinerja Omzet Per Cabang');
    }
}
