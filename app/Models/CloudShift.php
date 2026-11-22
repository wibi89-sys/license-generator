<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudShift extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'id',
        'branch_code',
        'terminal_id',
        'cashier_name',
        'opened_at',
        'closed_at',
        'opening_cash',
        'actual_cash',
        'cash_difference',
        'status',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
}
