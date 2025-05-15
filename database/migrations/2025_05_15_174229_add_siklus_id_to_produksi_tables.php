<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambahkan kolom siklus_id ke tabel bibits
        Schema::table('bibits', function (Blueprint $table) {
            $table->foreignId('siklus_id')
                  ->constrained('siklus')
                  ->onDelete('cascade');
        });

        // Tambahkan kolom siklus_id ke tabel pakans
        Schema::table('pakans', function (Blueprint $table) {
            $table->foreignId('siklus_id')
                  ->constrained('siklus')
                  ->onDelete('cascade');
        });

        // Tambahkan kolom siklus_id ke tabel panens
        Schema::table('panens', function (Blueprint $table) {
            $table->foreignId('siklus_id')
                  ->constrained('siklus')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        // Hapus foreign key dan kolom saat rollback
        Schema::table('bibits', function (Blueprint $table) {
            $table->dropForeign(['siklus_id']);
            $table->dropColumn('siklus_id');
        });

        Schema::table('pakans', function (Blueprint $table) {
            $table->dropForeign(['siklus_id']);
            $table->dropColumn('siklus_id');
        });

        Schema::table('panens', function (Blueprint $table) {
            $table->dropForeign(['siklus_id']);
            $table->dropColumn('siklus_id');
        });
    }
};
