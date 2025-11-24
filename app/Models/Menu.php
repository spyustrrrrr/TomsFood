<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    
    // Disable updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;
    
    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    // Relationship: Menu belongs to Restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    // Relationship: Menu has many OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_id');
    }

    // Helper method: Format price to Rupiah
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}