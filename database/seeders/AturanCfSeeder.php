<?php

namespace Database\Seeders;

use App\Models\AturanCf;
use Illuminate\Database\Seeder;
// use App\Models\Gejala;
// use App\Models\PenyakitHama;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AturanCfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Mapping Manual agar Akurat (Penyakit ID sesuai urutan PenyakitHamaSeeder)
    // 1: Bulai, 2: Karat, 3: Hawar, 4: Busuk Batang, 6: Ulat Grayak, 7: Penggerek Batang, dst.
    $rules = [
        // Bulai Jagung (P1) -> Daun menguning (G01), Daun bergaris kuning (G15)
        ['penyakit_id' => 1, 'gejala_id' => 1, 'mb' => 0.8, 'md' => 0.1],
        ['penyakit_id' => 1, 'gejala_id' => 15, 'mb' => 0.9, 'md' => 0.1],

        // Karat Daun (P2) -> Pustul karat (G03), Bercak coklat (G02)
        ['penyakit_id' => 2, 'gejala_id' => 3, 'mb' => 0.9, 'md' => 0.1],
        ['penyakit_id' => 2, 'gejala_id' => 2, 'mb' => 0.7, 'md' => 0.2],

        // Ulat Grayak (P6) -> Daun berlubang (G04), Daun rusak transparan (G05)
        ['penyakit_id' => 6, 'gejala_id' => 4, 'mb' => 0.8, 'md' => 0.1],
        ['penyakit_id' => 6, 'gejala_id' => 5, 'mb' => 0.8, 'md' => 0.1],

        // Penggerek Batang (P7) -> Batang berlubang (G09), Larva di batang (G12)
        ['penyakit_id' => 7, 'gejala_id' => 9, 'mb' => 0.9, 'md' => 0.1],
        ['penyakit_id' => 7, 'gejala_id' => 12, 'mb' => 0.9, 'md' => 0.0],
        
        // Penggerek Tongkol (P8) -> Tongkol rusak (G13), Biji busuk (G14)
        ['penyakit_id' => 8, 'gejala_id' => 13, 'mb' => 0.8, 'md' => 0.1],
        ['penyakit_id' => 8, 'gejala_id' => 14, 'mb' => 0.8, 'md' => 0.1],
    ];

    foreach ($rules as $rule) {
        AturanCf::create($rule);
    }
}
}
