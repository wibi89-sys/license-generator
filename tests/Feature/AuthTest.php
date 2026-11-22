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
}
