<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->unsignedBigInteger('id_tutor');
            $table->unsignedBigInteger('id_sekolah');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->date('tanggal')->nullable();
            $table->timestamps();

            $table->foreign('id_tutor')->references('id_tutor')->on('tutor')->onDelete('restrict');
            $table->foreign('id_sekolah')->references('id_sekolah')->on('sekolah')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
