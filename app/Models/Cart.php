<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    
    protected $fillable = [
        'user_id',
        'menu_id',
        'restaurant_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relationship: Cart belongs to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: Cart belongs to Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Relationship: Cart belongs to Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    // Get subtotal for this cart item
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Helper method: Format subtotal to Rupiah
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Helper method: Format price to Rupiah
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}