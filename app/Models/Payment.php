<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    // Tidak ada timestamps
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'payment_time',
        'total_paid',
    ];

    protected $casts = [
        'payment_time' => 'datetime',
        'total_paid' => 'decimal:2',
    ];

    // Relationship: Payment belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Helper method: Check if payment is completed
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    // Helper method: Format total paid to Rupiah
    public function getFormattedTotalPaidAttribute()
    {
        return 'Rp ' . number_format($this->total_paid, 0, ',', '.');
    }
}