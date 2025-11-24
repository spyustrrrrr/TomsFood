<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('menu_name', 100);
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
        });

        // Add computed column untuk total
        DB::statement('ALTER TABLE order_items ADD COLUMN total DECIMAL(10,2) AS (quantity * price) STORED');
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};