<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Dosen::create([
            'NIP' => '196201051990031002',
            'nama_dosen' => 'Budi Harijanto, ST., M.MKom.',
            // 'telp' => '08123313847',
            'telp' => purify_phone_number("082331296516"),
            'alamat' => 'Batu',
            'user_id' => 2,
        ]);

        Dosen::create([
            'NIP' => '197710302005012001',
            'nama_dosen' => 'Mungki Astiningrum, ST., M.Kom.',
            // 'telp' => '08123313847',
            'telp' => purify_phone_number("082331296516"),
            'alamat' => 'Batu',
            'user_id' => 3,
        ]);
    }
}
