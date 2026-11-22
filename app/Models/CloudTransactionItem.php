<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CloudTransactionItem extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'id',
        'cloud_transaction_id',
        'product_name',
        'batch_number',
        'qty',
        'unit_name',
        'unit_price',
        'hpp_unit',
        'subtotal',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CloudTransaction::class, 'cloud_transaction_id');
    }
}
