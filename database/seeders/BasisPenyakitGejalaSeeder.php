<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasisPenyakitGejalaSeeder extends Seeder
{
    public function run()
    {
        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $penyakit = DB::table('penyakit_hama')->pluck('id', 'nama_penyakit');

        $data = [
            // Ulat Grayak (6 gejala)
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G01']],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G02']],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G03']],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G04']],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G05']],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'], 'gejala_id' => $gejala['G06']],

            // Penggerek Batang Jagung (6 gejala)
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G07']],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G08']],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G09']],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G10']],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G11']],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang Jagung'], 'gejala_id' => $gejala['G12']],

            // Penyakit Bulai (4 gejala)
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'], 'gejala_id' => $gejala['G13']],
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'], 'gejala_id' => $gejala['G14']],
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'], 'gejala_id' => $gejala['G18']],
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'], 'gejala_id' => $gejala['G11']],

            // Hawar Daun (4 gejala)
            ['penyakit_hama_id' => $penyakit['Hawar Daun'], 'gejala_id' => $gejala['G16']],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'], 'gejala_id' => $gejala['G17']],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'], 'gejala_id' => $gejala['G18']],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'], 'gejala_id' => $gejala['G10']],

            // Karat Daun (3 gejala)
            ['penyakit_hama_id' => $penyakit['Karat Daun'], 'gejala_id' => $gejala['G25']],
            ['penyakit_hama_id' => $penyakit['Karat Daun'], 'gejala_id' => $gejala['G13']],
            ['penyakit_hama_id' => $penyakit['Karat Daun'], 'gejala_id' => $gejala['G18']],

            // Busuk Tongkol (4 gejala)
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'], 'gejala_id' => $gejala['G20']],
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'], 'gejala_id' => $gejala['G21']],
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'], 'gejala_id' => $gejala['G24']],
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'], 'gejala_id' => $gejala['G12']],

            // Busuk Batang (4 gejala)
            ['penyakit_hama_id' => $penyakit['Busuk Batang'], 'gejala_id' => $gejala['G19']],
            ['penyakit_hama_id' => $penyakit['Busuk Batang'], 'gejala_id' => $gejala['G22']],
            ['penyakit_hama_id' => $penyakit['Busuk Batang'], 'gejala_id' => $gejala['G23']],
            ['penyakit_hama_id' => $penyakit['Busuk Batang'], 'gejala_id' => $gejala['G10']],

            // Embun Tepung (4 gejala)
            ['penyakit_hama_id' => $penyakit['Penyakit Embun Tepung'], 'gejala_id' => $gejala['G15']],
            ['penyakit_hama_id' => $penyakit['Penyakit Embun Tepung'], 'gejala_id' => $gejala['G13']],
            ['penyakit_hama_id' => $penyakit['Penyakit Embun Tepung'], 'gejala_id' => $gejala['G14']],
            ['penyakit_hama_id' => $penyakit['Penyakit Embun Tepung'], 'gejala_id' => $gejala['G30']],
        ];

        DB::table('basis_penyakit_gejala')->insert($data);
    }
}