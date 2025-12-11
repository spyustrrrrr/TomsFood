<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    /**
     * File: app/Models/RestaurantTable.php
     * OPSIONAL: Untuk management meja secara detail
     */
    protected $table = 'restaurant_tables';
    
    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'status',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    /**
     * Table belongs to Restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Cek apakah meja tersedia
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Cek apakah meja sedang direservasi
     */
    public function isReserved()
    {
        return $this->status === 'reserved';
    }

    /**
     * Cek apakah meja sedang dipakai
     */
    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    /**
     * Cek apakah meja dalam maintenance
     */
    public function isMaintenance()
    {
        return $this->status === 'maintenance';
    }

    /**
     * Status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => '<span class="badge bg-success">Tersedia</span>',
            'reserved' => '<span class="badge bg-warning">Direservasi</span>',
            'occupied' => '<span class="badge bg-danger">Terisi</span>',
            'maintenance' => '<span class="badge bg-secondary">Maintenance</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">' . ucfirst($this->status) . '</span>';
    }

    // ============================================
    // SCOPES
    // ============================================
    
    /**
     * Scope untuk meja yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope untuk meja dengan kapasitas minimal
     */
    public function scopeWithMinCapacity($query, $minCapacity)
    {
        return $query->where('capacity', '>=', $minCapacity);
    }

    /**
     * Scope untuk meja by restaurant
     */
    public function scopeByRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }
}