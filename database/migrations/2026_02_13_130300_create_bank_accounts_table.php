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
        Schema::create('bank_accounts', function (Blueprint $row) {
            $row->id();
            $row->string('name'); // Account holder name
            $row->string('bank_name');
            $row->string('account_number')->nullable();
            $row->string('iban')->nullable();
            $row->boolean('is_active')->default(true);
            $row->boolean('is_online_default')->default(false);
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
