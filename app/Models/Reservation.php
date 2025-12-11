<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    /**
     * File: app/Models/Reservation.php
     */
    protected $table = 'reservations';
    
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'reservation_date',
        'guest_count',
        'table_number',
        'status',
        'special_request',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'guest_count' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    /**
     * Reservasi belongs to User (Customer)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Reservasi belongs to Restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    /**
     * Reservasi has one Order (Pre-order)
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'reservation_id');
    }

    // ============================================
    // HELPER METHODS - STATUS
    // ============================================
    
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPreparing()
    {
        return $this->status === 'preparing';
    }

    public function isReady()
    {
        return $this->status === 'ready';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isNoShow()
    {
        return $this->status === 'no_show';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    // ============================================
    // HELPER METHODS - DATE & TIME
    // ============================================
    
    /**
     * Format tanggal reservasi (Indonesia)
     */
    public function getFormattedDateAttribute()
    {
        return $this->reservation_date->translatedFormat('l, d F Y - H:i');
    }

    /**
     * Cek apakah reservasi sudah lewat
     */
    public function isPast()
    {
        return $this->reservation_date->isPast();
    }

    /**
     * Cek apakah reservasi hari ini
     */
    public function isToday()
    {
        return $this->reservation_date->isToday();
    }

    /**
     * Waktu hingga reservasi (human readable)
     */
    public function getTimeUntilAttribute()
    {
        if ($this->isPast()) {
            return 'Sudah lewat';
        }
        return $this->reservation_date->diffForHumans();
    }

    // ============================================
    // SCOPES
    // ============================================
    
    /**
     * Scope untuk reservasi yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>', now())
                     ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
    }

    /**
     * Scope untuk reservasi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('reservation_date', today());
    }

    /**
     * Scope untuk reservasi by customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope untuk reservasi by restaurant
     */
    public function scopeByRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    // ============================================
    // STATIC METHODS
    // ============================================
    
    /**
     * Status badge untuk tampilan
     */
    public static function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Menunggu Konfirmasi</span>',
            'confirmed' => '<span class="badge bg-info">Dikonfirmasi</span>',
            'preparing' => '<span class="badge bg-primary">Sedang Disiapkan</span>',
            'ready' => '<span class="badge bg-success">Siap</span>',
            'customer_arrived' => '<span class="badge bg-success">Customer Datang</span>',
            'completed' => '<span class="badge bg-secondary">Selesai</span>',
            'cancelled' => '<span class="badge bg-danger">Dibatalkan</span>',
            'no_show' => '<span class="badge bg-dark">Tidak Datang</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-light">' . ucfirst($status) . '</span>';
    }

    /**
     * Daftar semua status
     */
    public static function getAllStatuses()
    {
        return [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Sedang Disiapkan',
            'ready' => 'Siap',
            'customer_arrived' => 'Customer Datang',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Datang',
        ];
    }
}