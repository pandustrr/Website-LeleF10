<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('panens', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('kuantitas');
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->foreignId('siklus_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('panens');
    }
};
