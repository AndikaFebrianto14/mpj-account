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
        Schema::create('account_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();         // contoh: kb, kb1, kb2, ps
            $table->unsignedBigInteger('kelkode_id'); // foreign ke account_categories
            $table->string('code_name');              // deskripsi kode
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('kelkode_id')->references('id')->on('account_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_codes');
    }
};
