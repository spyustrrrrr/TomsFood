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
        'snap_token', // NEW
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

    // Helper method: Check if payment is pending
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    // Helper method: Check if payment failed
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    // Helper method: Format total paid to Rupiah
    public function getFormattedTotalPaidAttribute()
    {
        return 'Rp ' . number_format($this->total_paid, 0, ',', '.');
    }

    // Helper method: Get status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Menunggu Pembayaran</span>',
            'paid' => '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Dibayar</span>',
            'failed' => '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Gagal</span>',
        ];

        return $badges[$this->payment_status] ?? '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">' . ucfirst($this->payment_status) . '</span>';
    }
}