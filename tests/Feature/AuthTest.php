<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $responseDashboard = $this->get('/admin/dashboard');
        $responseDashboard->assertRedirect('/login');

        $responseLicenses = $this->get('/admin/licenses');
        $responseLicenses->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200)
            ->assertSee('Masuk ke Akun Admin');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@apotek.cloud',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@apotek.cloud',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@apotek.cloud',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@apotek.cloud',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_view_license_management_and_create_license(): void
    {
        $user = User::factory()->create();

        $responseIndex = $this->actingAs($user)->get('/admin/licenses');
        $responseIndex->assertStatus(200)
            ->assertSee('Universal License Server')
            ->assertSee('Semua Produk');

        $responseCreate = $this->actingAs($user)->get('/admin/licenses/create');
        $responseCreate->assertStatus(200)
            ->assertSee('Terbitkan Lisensi Baru')
            ->assertSee('Target Produk Aplikasi');

        $responseStore = $this->actingAs($user)->post('/admin/licenses', [
            'product_code' => 'RETAIL_POS',
            'customer_name' => 'Pak Joko',
            'business_name' => 'Minimarket Barokah',
            'branch_code' => 'BR01',
            'plan_type' => 'PRO_CLOUD_SYNC',
            'billing_type' => 'subscription',
            'max_reset_count' => 3,
        ]);

        $responseStore->assertRedirect(route('admin.licenses.index'));
        $this->assertDatabaseHas('licenses', [
            'customer_name' => 'Pak Joko',
            'business_name' => 'Minimarket Barokah',
            'product_code' => 'RETAIL_POS',
        ]);
    }

    public function test_authenticated_user_can_view_edit_update_and_delete_license(): void
    {
        $user = User::factory()->create();

        // 1. Create a license
        $license = \App\Models\License::create([
            'license_key' => 'APT-TEST-1234-5678',
            'product_code' => \App\Enums\AppProduct::APOTEK_POS,
            'customer_name' => 'Budi Santoso',
            'business_name' => 'Apotek Sehat',
            'pharmacy_name' => 'Apotek Sehat',
            'branch_code' => 'BR01',
            'plan_type' => \App\Enums\LicensePlan::STANDARD_LOCAL,
            'billing_type' => 'lifetime',
            'limits_json' => ['users' => 3],
            'features_json' => ['pos' => true],
            'status' => 'active',
            'max_reset_count' => 2,
            'reset_used' => 0,
        ]);

        // 2. View Detail (Show)
        $responseShow = $this->actingAs($user)->get("/admin/licenses/{$license->id}");
        $responseShow->assertStatus(200)
            ->assertSee('APT-TEST-1234-5678')
            ->assertSee('Apotek Sehat');

        // 3. View Edit Form
        $responseEdit = $this->actingAs($user)->get("/admin/licenses/{$license->id}/edit");
        $responseEdit->assertStatus(200)
            ->assertSee('Edit Lisensi');

        // 4. Update License
        $responseUpdate = $this->actingAs($user)->put("/admin/licenses/{$license->id}", [
            'customer_name' => 'Budi Santoso Updated',
            'business_name' => 'Apotek Sehat Prima',
            'branch_code' => 'BR02',
            'plan_type' => 'PRO_CLOUD_SYNC',
            'billing_type' => 'subscription',
            'status' => 'suspended',
            'max_reset_count' => 5,
        ]);

        $responseUpdate->assertRedirect(route('admin.licenses.show', $license));
        $this->assertDatabaseHas('licenses', [
            'id' => $license->id,
            'customer_name' => 'Budi Santoso Updated',
            'business_name' => 'Apotek Sehat Prima',
            'branch_code' => 'BR02',
            'status' => 'suspended',
            'max_reset_count' => 5,
        ]);

        // 5. Toggle Status
        $responseToggle = $this->actingAs($user)->post("/admin/licenses/{$license->id}/toggle-status", [
            'status' => 'active',
        ]);
        $responseToggle->assertRedirect();
        $this->assertDatabaseHas('licenses', [
            'id' => $license->id,
            'status' => 'active',
        ]);

        // 6. Delete License (Destroy)
        $responseDestroy = $this->actingAs($user)->delete("/admin/licenses/{$license->id}");
        $responseDestroy->assertRedirect(route('admin.licenses.index'));
        $this->assertDatabaseMissing('licenses', [
            'id' => $license->id,
        ]);
    }
}
