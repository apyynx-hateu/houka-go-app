<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_tutor');
            $table->unsignedBigInteger('id_jadwal')->nullable();
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Tidak Hadir']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_tutor')->references('id_tutor')->on('tutor')->onDelete('restrict');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('restrict');
            $table->unique(['id_tutor', 'id_jadwal', 'tanggal'], 'absensi_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
