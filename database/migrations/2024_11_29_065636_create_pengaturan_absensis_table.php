<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaturan_absensis', function (Blueprint $table) {
            $table->id();
            $table->time('waktu_buka', 1);
            $table->time('waktu_tutup', 1);
            $table->string('rentang_awal_IP', 50);
            $table->string('rentang_akhir_IP', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengaturan_absensis');
    }
};
