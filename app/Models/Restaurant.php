<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Restaurant extends Model
{
    /**
     * File: app/Models/Restaurant.php (UPDATED)
     */
    protected $table = 'restaurants';
    
    // Disable updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;
    
    protected $fillable = [
        'name',
        'description',
        'image',
        'rating',
        'table_capacity',        // BARU
        'opening_hours',         // BARU
        'closing_hours',         // BARU
        'booking_advance_hours', // BARU
        'phone',                 // BARU
        'address',               // BARU
        'latitude',              // BARU
        'longitude',             // BARU
    ];

    protected $casts = [
        'rating' => 'float',
        'table_capacity' => 'integer',
        'booking_advance_hours' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    /**
     * Restaurant has many Menus
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurant_id');
    }

    /**
     * Restaurant has many Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_id');
    }

    /**
     * Restaurant has many Reservations (BARU)
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'restaurant_id');
    }

    /**
     * Restaurant has many Tables (OPSIONAL - jika pakai table management)
     */
    public function tables()
    {
        return $this->hasMany(RestaurantTable::class, 'restaurant_id');
    }

    // ============================================
    // HELPER METHODS - OPERATIONAL HOURS
    // ============================================
    
    /**
     * Format jam buka (09:00)
     */
    public function getFormattedOpeningHoursAttribute()
    {
        return Carbon::parse($this->opening_hours)->format('H:i');
    }

    /**
     * Format jam tutup (22:00)
     */
    public function getFormattedClosingHoursAttribute()
    {
        return Carbon::parse($this->closing_hours)->format('H:i');
    }

    /**
     * Cek apakah restoran sedang buka
     */
    public function isOpen()
    {
        $now = Carbon::now()->format('H:i:s');
        return $now >= $this->opening_hours && $now <= $this->closing_hours;
    }

    /**
     * Cek apakah restoran tutup
     */
    public function isClosed()
    {
        return !$this->isOpen();
    }

    /**
     * Minimal waktu booking dari sekarang
     */
    public function getMinimumBookingTimeAttribute()
    {
        return now()->addHours($this->booking_advance_hours);
    }

    // ============================================
    // HELPER METHODS - AVAILABILITY
    // ============================================
    
    /**
     * Cek ketersediaan meja pada tanggal tertentu
     */
    public function hasAvailableTable($date, $guestCount = 1)
    {
        // Hitung reservasi yang sudah ada pada tanggal tersebut
        $existingReservations = $this->reservations()
            ->whereDate('reservation_date', Carbon::parse($date)->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->sum('guest_count');

        // Total kapasitas vs yang sudah terisi
        $availableCapacity = $this->table_capacity - $existingReservations;

        return $availableCapacity >= $guestCount;
    }

    /**
     * Hitung sisa kapasitas meja
     */
    public function getRemainingCapacity($date)
    {
        $existingReservations = $this->reservations()
            ->whereDate('reservation_date', Carbon::parse($date)->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->sum('guest_count');

        return max(0, $this->table_capacity - $existingReservations);
    }

    // ============================================
    // SCOPES
    // ============================================
    
    /**
     * Scope untuk restoran yang sedang buka
     */
    public function scopeOpen($query)
    {
        $now = Carbon::now()->format('H:i:s');
        return $query->where('opening_hours', '<=', $now)
                     ->where('closing_hours', '>=', $now);
    }

    /**
     * Scope untuk restoran dengan rating tinggi
     */
    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope untuk restoran dengan kapasitas tertentu
     */
    public function scopeWithCapacity($query, $minCapacity)
    {
        return $query->where('table_capacity', '>=', $minCapacity);
    }

    // ============================================
    // HELPER METHODS - DISPLAY
    // ============================================
    
    /**
     * Rating dengan bintang
     */
    public function getStarRatingAttribute()
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        $stars = str_repeat('⭐', $fullStars);
        if ($halfStar) {
            $stars .= '⭐'; // bisa pakai icon half star
        }
        $stars .= str_repeat('☆', $emptyStars);

        return $stars . ' (' . number_format($this->rating, 1) . ')';
    }

    /**
     * Format alamat lengkap
     */
    public function getFullAddressAttribute()
    {
        return $this->address ?? 'Alamat tidak tersedia';
    }

    /**
     * Google Maps link
     */
    public function getMapLinkAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }
}