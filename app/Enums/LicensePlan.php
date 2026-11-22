<?php

declare(strict_types=1);

namespace App\Enums;

enum LicensePlan: string
{
    case STANDARD_LOCAL = 'STANDARD_LOCAL';
    case PRO_CLOUD_SYNC = 'PRO_CLOUD_SYNC';
    case ENTERPRISE = 'ENTERPRISE';

    public function defaultLimits(): array
    {
        return match ($this) {
            self::STANDARD_LOCAL => [
                'max_terminals' => 2,
                'max_branches' => 1,
                'max_users' => 5,
            ],
            self::PRO_CLOUD_SYNC => [
                'max_terminals' => 5,
                'max_branches' => 1,
                'max_users' => 15,
            ],
            self::ENTERPRISE => [
                'max_terminals' => null,
                'max_branches' => 10,
                'max_users' => null,
            ],
        };
    }

    public function defaultFeatures(): array
    {
        return match ($this) {
            self::STANDARD_LOCAL => [
                'fingerprint_auth' => false,
                'label_printing' => false,
                'offsite_backup' => false,
                'cloud_sync_push' => false,
                'sipnap_export' => false,
                'profit_analytics' => false,
                'cloud_sync_pull' => false,
                'multi_branch_ops' => false,
                'external_api' => false,
            ],
            self::PRO_CLOUD_SYNC => [
                'fingerprint_auth' => true,
                'label_printing' => true,
                'offsite_backup' => true,
                'cloud_sync_push' => true,
                'sipnap_export' => true,
                'profit_analytics' => true,
                'cloud_sync_pull' => false,
                'multi_branch_ops' => false,
                'external_api' => false,
            ],
            self::ENTERPRISE => [
                'fingerprint_auth' => true,
                'label_printing' => true,
                'offsite_backup' => true,
                'cloud_sync_push' => true,
                'sipnap_export' => true,
                'profit_analytics' => true,
                'cloud_sync_pull' => true,
                'multi_branch_ops' => true,
                'external_api' => true,
            ],
        };
    }

    public function defaultBillingType(): string
    {
        return match ($this) {
            self::STANDARD_LOCAL => 'lifetime',
            self::PRO_CLOUD_SYNC, self::ENTERPRISE => 'subscription',
        };
    }

    public function defaultHeartbeatIntervalDays(): int
    {
        return match ($this) {
            self::STANDARD_LOCAL => 0,
            self::PRO_CLOUD_SYNC, self::ENTERPRISE => 7,
        };
    }
}
