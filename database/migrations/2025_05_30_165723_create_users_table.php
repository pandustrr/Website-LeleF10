<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 25)->unique();
            $table->string('password');
            $table->string('tempat_tanggal_lahir', 50);
            $table->text('alamat');
            $table->string('nomor_telepon', 15);
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
