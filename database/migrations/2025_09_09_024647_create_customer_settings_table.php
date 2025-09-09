<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_total_remaining_in_invoice')->default(false);
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_settings');
    }
};
