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
        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('description')->change();
        });
    }
};
