<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 45);
            $table->string('jadwal')->nullable(); // Sesuaikan dengan tipe data yang sesuai
            $table->timestamps();
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade'); // Kolom untuk menghubungkan ke tabel dosen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
