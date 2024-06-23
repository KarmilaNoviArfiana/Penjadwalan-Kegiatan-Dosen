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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 45);
            $table->enum('tugas', [
                'Direktur',
                'Pudir 1',
                'Pudir 2',
                'Pudir 3',
                'Jurusan',
            ]);
            $table->string('nama_kegiatan', 100);
            $table->string('Tempat', 100);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('surat_tugas')->nullable(); // Sesuaikan dengan tipe data yang sesuai
            $table->enum('status_kehadiran', ['Hadir','Tidak Hadir',])->default('Hadir');
            $table->string('keterangan', 100);
            $table->string('bukti')->nullable();
            $table->timestamps();
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade'); // Kolom untuk menghubungkan ke tabel dosen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
