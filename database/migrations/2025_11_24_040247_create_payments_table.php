<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method', 50);
            $table->string('payment_status', 20)->default('unpaid');
            $table->dateTime('payment_time')->nullable();
            $table->decimal('total_paid', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};