<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nips = ['196201051990031002', '197710302005012001'];
        $tugas_dari = ['Direktur', 'Pudir 1', 'Pudir 2', 'Pudir 3', 'Jurusan'];

        for ($i = 0; $i < 1000; $i++) {
            Kegiatan::create([
                'NIP' => $nips[random_int(0, 1)],
                'tugas' => $tugas_dari[random_int(0, 4)],
                'nama_kegiatan' => 'Kegiatan ke ' . $i,
                'tanggal' => date("Y-m-d", strtotime(date("Y-m-d") . " -$i day")),
                'waktu_mulai' => '10:00:00',
                'waktu_selesai' => '14:00:00',
                'surat_tugas' => null,
            ]);
        }
    }
}
