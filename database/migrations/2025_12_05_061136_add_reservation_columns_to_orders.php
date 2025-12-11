<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * File: database/migrations/xxxx_add_reservation_columns_to_orders.php
     * (Replace isi file yang error dengan kode ini)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek apakah kolom reservation_id sudah ada
            if (!Schema::hasColumn('orders', 'reservation_id')) {
                $table->foreignId('reservation_id')
                      ->nullable()
                      ->after('restaurant_id')
                      ->constrained('reservations')
                      ->onDelete('cascade');
                
                $table->index('reservation_id');
            }
            
            // Cek apakah kolom order_type sudah ada
            if (!Schema::hasColumn('orders', 'order_type')) {
                $table->enum('order_type', ['pre_order', 'dine_in'])
                      ->default('dine_in')
                      ->after('reservation_id');
            }
            
            // Cek apakah kolom preparation_time sudah ada
            if (!Schema::hasColumn('orders', 'preparation_time')) {
                $table->integer('preparation_time')
                      ->nullable()
                      ->after('order_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'reservation_id')) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            }
            
            if (Schema::hasColumn('orders', 'order_type')) {
                $table->dropColumn('order_type');
            }
            
            if (Schema::hasColumn('orders', 'preparation_time')) {
                $table->dropColumn('preparation_time');
            }
        });
    }
};