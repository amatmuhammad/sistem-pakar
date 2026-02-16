<?php

namespace Database\Seeders;

use App\Models\Gejala;
use App\Models\AturanCf;
use App\Models\PenyakitHama;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AturanCfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $gejala = Gejala::pluck('id')->toArray();
        $penyakit = PenyakitHama::pluck('id')->toArray();

        $data = [];

        for ($i = 1; $i <= 50; $i++) {

            $data[] = [
                'penyakit_id' => $penyakit[array_rand($penyakit)],
                'gejala_id'   => $gejala[array_rand($gejala)],
                'mb' => rand(5,10)/10,
                'md' => rand(0,5)/10,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        AturanCf::insert($data);
    }
}
