<?php

declare(strict_types=1);

namespace App\Enums;

enum LicenseFeature: string
{
    case FINGERPRINT_AUTH = 'fingerprint_auth';
    case LABEL_PRINTING = 'label_printing';
    case OFFSITE_BACKUP = 'offsite_backup';
    case CLOUD_SYNC_PUSH = 'cloud_sync_push';
    case SIPNAP_EXPORT = 'sipnap_export';
    case PROFIT_ANALYTICS = 'profit_analytics';
    case CLOUD_SYNC_PULL = 'cloud_sync_pull';
    case MULTI_BRANCH_OPS = 'multi_branch_ops';
    case EXTERNAL_API = 'external_api';

    public function label(): string
    {
        return match ($this) {
            self::FINGERPRINT_AUTH => 'Autentikasi Fingerprint',
            self::LABEL_PRINTING => 'Cetak Label Barcode',
            self::OFFSITE_BACKUP => 'Backup Otomatis Offsite',
            self::CLOUD_SYNC_PUSH => 'Sinkronisasi Push Cloud',
            self::SIPNAP_EXPORT => 'Ekspor Laporan Regulasi (SIPNAP)',
            self::PROFIT_ANALYTICS => 'Analisis Laporan & Performa',
            self::CLOUD_SYNC_PULL => 'Sinkronisasi Pull Cloud',
            self::MULTI_BRANCH_OPS => 'Operasional Multi-Cabang',
            self::EXTERNAL_API => 'Akses API Eksternal',
        };
    }
}
