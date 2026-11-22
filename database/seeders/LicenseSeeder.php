<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AppProduct;
use App\Enums\LicensePlan;
use App\Models\License;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'product_code' => AppProduct::APOTEK_POS,
                'license_key' => 'APT-DEMO-2026-LIVE',
                'customer_name' => 'Apt. Pengguna Demo',
                'business_name' => 'Apotek Pos Sehat Sentosa',
                'pharmacy_name' => 'Apotek Pos Sehat Sentosa',
                'plan_type' => LicensePlan::PRO_CLOUD_SYNC,
            ],
            [
                'product_code' => AppProduct::RETAIL_POS,
                'license_key' => 'WRG-DEMO-2026-LIVE',
                'customer_name' => 'H. Sukses Mandiri',
                'business_name' => 'Minimarket & Toko Grosir Sukses',
                'pharmacy_name' => 'Minimarket & Toko Grosir Sukses',
                'plan_type' => LicensePlan::PRO_CLOUD_SYNC,
            ],
            [
                'product_code' => AppProduct::ATTENDANCE_APP,
                'license_key' => 'ABS-DEMO-2026-LIVE',
                'customer_name' => 'HRD PT Berkah Utama',
                'business_name' => 'PT Berkah Utama Jaya',
                'pharmacy_name' => 'PT Berkah Utama Jaya',
                'plan_type' => LicensePlan::PRO_CLOUD_SYNC,
            ],
            [
                'product_code' => AppProduct::CLINIC_APP,
                'license_key' => 'KLN-DEMO-2026-LIVE',
                'customer_name' => 'dr. Budi Raharja',
                'business_name' => 'Klinik Pratama Sehat Mandiri',
                'pharmacy_name' => 'Klinik Pratama Sehat Mandiri',
                'plan_type' => LicensePlan::ENTERPRISE,
            ],
        ];

        foreach ($products as $p) {
            $plan = $p['plan_type'];
            $prod = $p['product_code'];

            License::updateOrCreate(
                ['license_key' => $p['license_key']],
                [
                    'product_code' => $prod,
                    'customer_name' => $p['customer_name'],
                    'business_name' => $p['business_name'],
                    'pharmacy_name' => $p['pharmacy_name'],
                    'branch_code' => 'BR01',
                    'plan_type' => $plan,
                    'billing_type' => 'subscription',
                    'limits_json' => $prod->defaultLimits($plan),
                    'features_json' => $prod->defaultFeatures($plan),
                    'status' => 'active',
                    'bound_hwid' => null, // Ready to be bound on first activation
                    'max_reset_count' => 3,
                    'reset_used' => 0,
                    'expires_at' => now()->addYear(),
                    'grace_days' => 14,
                    'heartbeat_interval_days' => 7,
                ]
            );
        }
    }
}
