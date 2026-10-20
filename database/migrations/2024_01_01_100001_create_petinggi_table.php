<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petinggi', function (Blueprint $table) {
            $table->id('id_petinggi');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('jabatan', ['CO Founder', 'Operasional', 'Human Resource']);
            $table->enum('status_aktif', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petinggi');
    }
};
