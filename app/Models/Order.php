<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    // Disable updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;
    
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relationship: Order belongs to User (Customer)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relationship: Order belongs to Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    // Relationship: Order has many OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Relationship: Order has one Payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    // Helper method: Format total to Rupiah
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}