<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CloudTransaction extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'id',
        'branch_code',
        'invoice_no',
        'cashier_name',
        'type',
        'subtotal',
        'tuslah_embalase_total',
        'discount_total',
        'grand_total',
        'hpp_total',
        'gross_profit',
        'payment_method',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'subtotal' => 'string',
        'tuslah_embalase_total' => 'string',
        'discount_total' => 'string',
        'grand_total' => 'string',
        'hpp_total' => 'string',
        'gross_profit' => 'string',
        'transaction_date' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CloudTransactionItem::class);
    }
}
