<?php

namespace Database\Seeders;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use App\Models\FiturKasusCbr;
use Illuminate\Database\Seeder;
use App\Models\HasilDiagnosisCbr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KasusCbrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $gejalaIds = Gejala::pluck('id')->toArray();
        $penyakitIds = PenyakitHama::pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {

            $kasus = KasusCbr::create([
                'user_id' => 1,
                'tanggal' => now(),
                'nilai_similarity' => rand(50,100)/100
            ]);

            // ambil jumlah gejala acak (3 - 8)
            $jumlahGejala = rand(3,8);

            $gejalaRandom = collect($gejalaIds)
                ->shuffle()
                ->take($jumlahGejala);

            foreach ($gejalaRandom as $idGejala) {
                FiturKasusCbr::create([
                    'kasus_cbr_id' => $kasus->id,
                    'gejala_id' => $idGejala,
                    'nilai' => 1
                ]);
            }

            HasilDiagnosisCbr::create([
                'kasus_cbr_id' => $kasus->id,
                'penyakit_id' => $penyakitIds[array_rand($penyakitIds)],
                'similarity_final' => rand(50,100)/100
            ]);
        }
    }
}
