<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honor', function (Blueprint $table) {
            $table->id('id_honor');
            $table->unsignedBigInteger('id_tutor');
            $table->date('periode_bulan');
            $table->decimal('tarif_per_jam', 12, 2)->default(0);
            $table->integer('jumlah_siswa')->unsigned()->default(0);
            $table->decimal('total_jam_mengajar', 8, 2)->default(0);
            $table->decimal('penyesuaian_honor', 12, 2)->default(0);
            $table->decimal('total_honor', 12, 2)->default(0);
            $table->enum('status', ['Belum Dibayar', 'Diproses', 'Dibayar'])->default('Belum Dibayar');
            $table->timestamps();

            $table->foreign('id_tutor')->references('id_tutor')->on('tutor')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honor');
    }
};
