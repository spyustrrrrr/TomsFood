<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = 'restaurants';
    
    // Disable updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;
    
    protected $fillable = [
        'name',
        'description',
        'image',
        'rating',
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    // Relationship: Restaurant has many Menus
    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurant_id');
    }

    // Relationship: Restaurant has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_id');
    }
}