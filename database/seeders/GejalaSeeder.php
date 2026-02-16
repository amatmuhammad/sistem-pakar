<?php

namespace Database\Seeders;

use App\Models\Gejala;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GejalaSeeder extends Seeder
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
                'kode_gejala' => 'G'.str_pad($i,3,'0',STR_PAD_LEFT),
                'nama_gejala' => 'Gejala ke-'.$i,
                'bobot_cbr' => rand(1,10)/10,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Gejala::insert($data);
    }
}
