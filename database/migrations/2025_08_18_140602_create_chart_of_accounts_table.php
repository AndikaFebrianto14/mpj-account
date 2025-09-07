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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();   // contoh: 1-001, 1-010.1
            $table->string('account_name');               // contoh: Kas Ditangan Jannati Residence II
            $table->enum('account_type', ['Debit', 'Riil']);
            $table->unsignedBigInteger('code_id');        // FK ke account_codes
            $table->unsignedBigInteger('kelkode_id');     // FK ke account_categories
            $table->enum('normal_balance', ['Debit', 'Credit']);
            $table->unsignedBigInteger('parent_account_id')->nullable(); // untuk akun induk
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('code_id')->references('id')->on('account_codes')->onDelete('cascade');
            $table->foreign('kelkode_id')->references('id')->on('account_categories')->onDelete('cascade');
            $table->foreign('parent_account_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
