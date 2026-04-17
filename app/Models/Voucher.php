<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_code',
        'discount_percent',
        'is_used',
        'valid_until'
    ];

    protected $casts = [
        'valid_until' => 'date',
    ];
}
