<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Kapasitas meja
            $table->integer('table_capacity')
                  ->default(10)
                  ->after('rating');
            
            // Jam operasional
            $table->time('opening_hours')
                  ->default('08:00:00')
                  ->after('table_capacity');
            
            $table->time('closing_hours')
                  ->default('22:00:00')
                  ->after('opening_hours');
            
            // Minimal booking berapa jam sebelumnya (dalam jam)
            $table->integer('booking_advance_hours')
                  ->default(2)
                  ->after('closing_hours')
                  ->comment('Minimal booking X jam sebelum kedatangan');
            
            // Informasi tambahan
            $table->string('phone', 20)
                  ->nullable()
                  ->after('booking_advance_hours');
            
            $table->text('address')
                  ->nullable()
                  ->after('phone');
            
            $table->decimal('latitude', 10, 7)
                  ->nullable()
                  ->after('address');
            
            $table->decimal('longitude', 10, 7)
                  ->nullable()
                  ->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'table_capacity',
                'opening_hours',
                'closing_hours',
                'booking_advance_hours',
                'phone',
                'address',
                'latitude',
                'longitude'
            ]);
        });
    }
};