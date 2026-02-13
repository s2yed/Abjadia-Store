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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // e.g., 'buy_x_get_y', 'percentage_off', 'fixed_amount'
            $table->json('conditions')->nullable(); // e.g., { "min_qty": 2, "product_ids": [1, 2] }
            $table->json('actions')->nullable(); // e.g., { "discount_percentage": 10, "free_product_id": 3 }
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
