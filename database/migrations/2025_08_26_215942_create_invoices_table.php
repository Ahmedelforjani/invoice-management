<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id()->from(10001);
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('status')->default('issued');
            $table->decimal('discount')->default(0);
            $table->decimal('subtotal_amount');
            $table->decimal('total_amount');
            $table->decimal('total_cost')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->date('issue_date')->default(now());
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
