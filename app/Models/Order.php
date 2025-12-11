<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * File: app/Models/Order.php (UPDATED)
     */
    protected $table = 'orders';
    
    // Disable updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;
    
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'reservation_id',    // BARU
        'order_type',        // BARU: pre_order atau dine_in
        'preparation_time',  // BARU
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'preparation_time' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    /**
     * Order belongs to User (Customer)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Order belongs to Restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    /**
     * Order belongs to Reservation (BARU)
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    /**
     * Order has many OrderItems
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Order has one Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Format total to Rupiah
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Cek apakah order adalah pre-order
     */
    public function isPreOrder()
    {
        return $this->order_type === 'pre_order';
    }

    /**
     * Cek apakah order adalah dine-in
     */
    public function isDineIn()
    {
        return $this->order_type === 'dine_in';
    }

    /**
     * Status badge untuk tampilan
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'confirmed' => '<span class="badge bg-info">Dikonfirmasi</span>',
            'preparing' => '<span class="badge bg-primary">Sedang Disiapkan</span>',
            'ready' => '<span class="badge bg-success">Siap</span>',
            'completed' => '<span class="badge bg-secondary">Selesai</span>',
            'cancelled' => '<span class="badge bg-danger">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">' . ucfirst($this->status) . '</span>';
    }

    // ============================================
    // SCOPES
    // ============================================
    
    /**
     * Scope untuk pre-order
     */
    public function scopePreOrder($query)
    {
        return $query->where('order_type', 'pre_order');
    }

    /**
     * Scope untuk dine-in
     */
    public function scopeDineIn($query)
    {
        return $query->where('order_type', 'dine_in');
    }

    /**
     * Scope untuk order yang masih aktif
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready']);
    }
}