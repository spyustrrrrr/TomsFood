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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->dateTime('reservation_date');
            $table->integer('guest_count');
            $table->string('table_number', 20)->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'customer_arrived',
                'completed',
                'cancelled',
                'no_show'
            ])->default('pending');
            $table->text('special_request')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index('customer_id');
            $table->index('restaurant_id');
            $table->index('reservation_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};