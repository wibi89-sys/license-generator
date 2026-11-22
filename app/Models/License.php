<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AppProduct;
use App\Enums\LicensePlan;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class License extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'license_key',
        'product_code',
        'customer_name',
        'pharmacy_name',
        'business_name',
        'branch_code',
        'plan_type',
        'billing_type',
        'limits_json',
        'features_json',
        'status',
        'bound_hwid',
        'bound_hostname',
        'max_reset_count',
        'reset_used',
        'activated_at',
        'expires_at',
        'grace_days',
        'heartbeat_interval_days',
        'last_heartbeat_at',
    ];

    protected $casts = [
        'product_code' => AppProduct::class,
        'plan_type' => LicensePlan::class,
        'limits_json' => 'array',
        'features_json' => 'array',
        'max_reset_count' => 'integer',
        'reset_used' => 'integer',
        'grace_days' => 'integer',
        'heartbeat_interval_days' => 'integer',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
    ];

    public function auditLogs(): HasMany
    {
        return $this->hasMany(LicenseAuditLog::class);
    }

    /**
     * Generate unique license key with product-specific prefix:
     * - APOTEK_POS: APT-XXXX-XXXX-XXXX
     * - RETAIL_POS: WRG-XXXX-XXXX-XXXX
     * - ATTENDANCE_APP: ABS-XXXX-XXXX-XXXX
     * - CLINIC_APP: KLN-XXXX-XXXX-XXXX
     */
    public static function generateKey(AppProduct|string|null $product = 'APOTEK_POS'): string
    {
        $productCode = $product instanceof AppProduct ? $product->value : (string) $product;
        $prefix = match (strtoupper($productCode)) {
            'RETAIL_POS', 'WARUNG_POS' => 'WRG',
            'ATTENDANCE_APP', 'ABSENSI' => 'ABS',
            'CLINIC_APP', 'KLINIK' => 'KLN',
            default => 'APT',
        };

        do {
            $key = sprintf(
                '%s-%s-%s-%s',
                $prefix,
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        } while (static::where('license_key', $key)->exists());

        return $key;
    }

    public function canResetHwid(): bool
    {
        return $this->reset_used < $this->max_reset_count;
    }

    public function resetHwid(?string $ip = null, ?string $notes = null): bool
    {
        if (! $this->canResetHwid()) {
            return false;
        }

        $oldHwid = $this->bound_hwid;
        $this->bound_hwid = null;
        $this->bound_hostname = null;
        $this->reset_used += 1;
        $this->save();

        $this->auditLogs()->create([
            'action' => 'hwid_reset',
            'hwid' => $oldHwid,
            'ip_address' => $ip,
            'notes' => $notes ?? 'HWID reset via Admin panel',
        ]);

        return true;
    }

    public function isExpired(): bool
    {
        if ($this->billing_type === 'lifetime' || $this->expires_at === null) {
            return false;
        }

        return now()->isAfter($this->expires_at->addDays($this->grace_days));
    }
}
