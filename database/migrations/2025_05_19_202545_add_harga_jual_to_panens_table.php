<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('panens', function (Blueprint $table) {
            $table->decimal('harga_jual', 12, 2)->nullable()->after('kuantitas');
        });
    }

    public function down()
    {
        Schema::table('panens', function (Blueprint $table) {
            $table->dropColumn('harga_jual');
        });
    }
};
