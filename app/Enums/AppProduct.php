<?php

declare(strict_types=1);

namespace App\Enums;

enum AppProduct: string
{
    case APOTEK_POS = 'APOTEK_POS';
    case RETAIL_POS = 'RETAIL_POS';
    case ATTENDANCE_APP = 'ATTENDANCE_APP';
    case CLINIC_APP = 'CLINIC_APP';

    public function prefix(): string
    {
        return match ($this) {
            self::APOTEK_POS => 'APT',
            self::RETAIL_POS => 'WRG',
            self::ATTENDANCE_APP => 'ABS',
            self::CLINIC_APP => 'KLN',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::APOTEK_POS => 'Apotek POS Pro',
            self::RETAIL_POS => 'POS Retail & Warung',
            self::ATTENDANCE_APP => 'Aplikasi Absensi & HRIS',
            self::CLINIC_APP => 'Aplikasi Klinik & Rekam Medis',
        };
    }

    public function defaultLimits(LicensePlan $plan): array
    {
        return match ($this) {
            self::APOTEK_POS => $plan->defaultLimits(),
            self::RETAIL_POS => [
                'max_terminals' => $plan === LicensePlan::STANDARD_LOCAL ? 1 : 5,
                'max_branches' => $plan === LicensePlan::ENTERPRISE ? 10 : 1,
                'max_users' => $plan === LicensePlan::STANDARD_LOCAL ? 3 : 15,
            ],
            self::ATTENDANCE_APP => [
                'max_employees' => $plan === LicensePlan::STANDARD_LOCAL ? 25 : ($plan === LicensePlan::PRO_CLOUD_SYNC ? 100 : null),
                'max_scanners' => $plan === LicensePlan::STANDARD_LOCAL ? 1 : 5,
                'max_branches' => $plan === LicensePlan::ENTERPRISE ? 20 : 1,
            ],
            self::CLINIC_APP => [
                'max_doctors' => $plan === LicensePlan::STANDARD_LOCAL ? 2 : 10,
                'max_poly' => $plan === LicensePlan::STANDARD_LOCAL ? 2 : 8,
                'max_terminals' => 5,
            ],
        };
    }

    public function defaultFeatures(LicensePlan $plan): array
    {
        return match ($this) {
            self::APOTEK_POS => $plan->defaultFeatures(),
            self::RETAIL_POS => [
                'barcode_scanner' => true,
                'receipt_printer' => true,
                'qris_payment' => true,
                'inventory_tracking' => true,
                'multi_cashier' => $plan !== LicensePlan::STANDARD_LOCAL,
                'cloud_sync' => $plan !== LicensePlan::STANDARD_LOCAL,
                'daily_profit_analytics' => $plan !== LicensePlan::STANDARD_LOCAL,
            ],
            self::ATTENDANCE_APP => [
                'u_are_u_fingerprint' => true,
                'face_recognition' => $plan === LicensePlan::ENTERPRISE,
                'gps_geofence' => true,
                'shift_scheduling' => true,
                'payroll_calculation' => $plan !== LicensePlan::STANDARD_LOCAL,
                'cloud_sync' => $plan !== LicensePlan::STANDARD_LOCAL,
                'export_excel' => true,
            ],
            self::CLINIC_APP => [
                'electronic_medical_record' => true,
                'patient_queue' => true,
                'icd10_diagnosis' => true,
                'prescription_sync' => true,
                'cloud_sync' => $plan !== LicensePlan::STANDARD_LOCAL,
                'bpjs_pcare_integration' => $plan === LicensePlan::ENTERPRISE,
            ],
        };
    }
}
