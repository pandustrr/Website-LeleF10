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
            $table->id(); // Kolom nomer (auto-increment)
            $table->date('tanggal'); // Kolom tanggal
            $table->integer('kuantitas'); // Kolom kuantitas
            $table->timestamps(); // Kolom created_at dan updated_at
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
