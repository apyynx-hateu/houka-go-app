<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->unsignedBigInteger('id_tutor');
            $table->unsignedBigInteger('id_petinggi');
            $table->date('tanggal_penilaian');
            $table->string('periode')->nullable();
            $table->text('kualitas_mengajar')->nullable();
            $table->text('catatan_kinerja')->nullable();
            $table->timestamps();

            $table->foreign('id_tutor')->references('id_tutor')->on('tutor')->onDelete('restrict');
            $table->foreign('id_petinggi')->references('id_petinggi')->on('petinggi')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
