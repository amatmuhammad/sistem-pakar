<?php

namespace Database\Seeders;

use App\Models\PenyakitHama;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenyakitHamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [];

        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama_penyakit' => 'Penyakit/Hama Jagung '.$i,
                'jenis' => $i % 2 == 0 ? 'hama' : 'penyakit',
                'deskripsi' => 'Deskripsi penyakit atau hama nomor '.$i,
                'solusi' => 'Solusi penanganan untuk penyakit/hama nomor '.$i,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        PenyakitHama::insert($data);
    }
}
