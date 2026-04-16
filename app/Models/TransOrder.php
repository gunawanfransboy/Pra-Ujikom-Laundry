<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransOrder extends Model
{
    use SoftDeletes;

    protected $table = 'trans_order';
    protected $fillable = [
        'id_customer', 'order_code', 'order_date', 'order_end_date',
        'order_status', 'subtotal', 'tax', 'order_pay', 'order_change', 'total'
    ];

    protected $casts = [
        'order_date' => 'date',
        'order_end_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            0 => 'Baru',
            1 => 'Sudah Diambil',
        ];
        return $statuses[$this->order_status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            0 => 'warning',
            1 => 'success',
        ];
        return $colors[$this->order_status] ?? 'secondary';
    }
}
