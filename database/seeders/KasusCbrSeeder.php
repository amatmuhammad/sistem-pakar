<?php

namespace Database\Seeders;

// use App\Models\Gejala;
// use App\Models\PenyakitHama;
use App\Models\KasusCbr;
use App\Models\FiturKasusCbr;
use Illuminate\Database\Seeder;
use App\Models\HasilDiagnosisCbr;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KasusCbrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kita buat Basis Kasus yang logic
        $basisKasus = [
            ['penyakit_id' => 1, 'gejala' => [1, 15, 6]], // Bulai
            ['penyakit_id' => 2, 'gejala' => [3, 2, 7]],  // Karat
            ['penyakit_id' => 6, 'gejala' => [4, 5, 11]], // Ulat Grayak
            ['penyakit_id' => 7, 'gejala' => [9, 12, 10]], // Penggerek Batang
            ['penyakit_id' => 8, 'gejala' => [13, 14, 19]], // Penggerek Tongkol
        ];

        foreach ($basisKasus as $data) {
            // Buat 10 variasi kasus untuk setiap penyakit agar database CBR kuat
            for ($i = 0; $i < 10; $i++) {
                $kasus = KasusCbr::create([
                    'user_id' => 1,
                    'tanggal' => now(),
                    'nilai_similarity' => 1.0
                ]);

                foreach ($data['gejala'] as $idGejala) {
                    FiturKasusCbr::create([
                        'kasus_cbr_id' => $kasus->id,
                        'gejala_id' => $idGejala,
                        'nilai' => 1
                    ]);
                }

                HasilDiagnosisCbr::create([
                    'kasus_cbr_id' => $kasus->id,
                    'penyakit_id' => $data['penyakit_id'],
                    'similarity_final' => 1.0
                ]);
            }
        }
    }
}
