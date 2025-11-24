<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
    // Tidak ada timestamps
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'menu_id',
        'menu_name',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relationship: OrderItem belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Relationship: OrderItem belongs to Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Accessor untuk total (karena di database ini computed column)
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Helper method: Format total to Rupiah
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}