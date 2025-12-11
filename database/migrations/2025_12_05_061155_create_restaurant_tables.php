<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * OPSIONAL: Untuk manage meja per restoran secara detail
     */
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('table_number', 20); // Contoh: "A1", "B3", "VIP-1"
            $table->integer('capacity'); // Kapasitas orang per meja
            $table->enum('status', ['available', 'reserved', 'occupied', 'maintenance'])
                  ->default('available');
            $table->text('notes')->nullable(); // Catatan khusus (misal: outdoor, smoking area)
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index('restaurant_id');
            $table->index('status');
            $table->unique(['restaurant_id', 'table_number']); // Table number unik per restoran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};