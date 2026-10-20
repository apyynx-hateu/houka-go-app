<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_honor', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_honor');
            $table->date('tanggal_pembayaran');
            $table->decimal('jumlah', 12, 2);
            $table->string('nomor_rekening')->nullable();
            $table->enum('status', ['Pending', 'Berhasil', 'Gagal'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_honor')->references('id_honor')->on('honor')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_honor');
    }
};
