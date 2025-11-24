<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship: User has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Helper method: Get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Helper method: Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Helper method: Check if user is customer
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}