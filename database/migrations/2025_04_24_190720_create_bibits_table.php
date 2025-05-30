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
        Schema::create('bibits', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('kuantitas');
            $table->string('type');

            // Tambahkan definisi kolom foreign key dulu
            $table->unsignedBigInteger('siklus_id');

            // Lalu definisikan relasinya
            $table->foreign('siklus_id')
                  ->references('id')
                  ->on('siklus')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibits');
    }
};
