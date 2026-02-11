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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Site Branding
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            
            // Site Information (Translatable)
            $table->json('site_name');
            $table->json('site_description')->nullable();
            $table->json('site_keywords')->nullable();

            // Contact Information
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            
            // Social Links
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_snapchat')->nullable();
            $table->string('social_youtube')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
