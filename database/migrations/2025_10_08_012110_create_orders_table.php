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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->from(10001);
            $table->string('status')->default('ordered');
            $table->decimal('total_amount');
            $table->decimal('subtotal_amount');
            $table->decimal('total_cost')->default(0);
            $table->text('notes')->nullable();
            $table->date('order_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
