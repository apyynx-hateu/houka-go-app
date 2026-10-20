<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_laporan', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->unsignedBigInteger('id_petinggi');
            $table->dateTime('waktu_permintaan');
            $table->enum('jenis', ['Rekap Tutor', 'Rekap Absensi', 'Rekap Jadwal', 'Rekap Honor', 'Informasi Operasional']);
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('id_petinggi')->references('id_petinggi')->on('petinggi')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_laporan');
    }
};
