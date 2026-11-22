<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudInventoryBatch extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'id',
        'branch_code',
        'product_name',
        'batch_number',
        'expired_date',
        'stock_qty',
        'unit_name',
        'buy_price',
        'synced_at',
    ];

    protected $casts = [
        'expired_date' => 'date',
        'synced_at' => 'datetime',
        'stock_qty' => 'integer',
    ];
}
