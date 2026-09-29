<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasisPenyakitGejalaSeeder extends Seeder
{
    public function run()
    {
        // 1. BERSIHKAN DATA LAMA
        DB::table('basis_penyakit_gejala')->delete();

        // 2. AMBIL ID DARI TABEL GEJALA DAN PENYAKIT
        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $penyakit = DB::table('penyakit_hama')->pluck('id', 'nama_penyakit');

        // 3. DAFTARKAN RELASI BARU BESERTA NILAI CF PAKAR
        $data = [
            // 1. Hama Uret (G01, G02, G03)
            ['penyakit_hama_id' => $penyakit['Hama Uret'] ?? null, 'gejala_id' => $gejala['G01'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Hama Uret'] ?? null, 'gejala_id' => $gejala['G02'] ?? null, 'cf_pakar' => 0.6],
            ['penyakit_hama_id' => $penyakit['Hama Uret'] ?? null, 'gejala_id' => $gejala['G03'] ?? null, 'cf_pakar' => 0.9],

            // 2. Ulat Tanah (G01, G02, G04, G05)
            ['penyakit_hama_id' => $penyakit['Ulat Tanah'] ?? null, 'gejala_id' => $gejala['G01'] ?? null, 'cf_pakar' => 0.7],
            ['penyakit_hama_id' => $penyakit['Ulat Tanah'] ?? null, 'gejala_id' => $gejala['G02'] ?? null, 'cf_pakar' => 0.6],
            ['penyakit_hama_id' => $penyakit['Ulat Tanah'] ?? null, 'gejala_id' => $gejala['G04'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Ulat Tanah'] ?? null, 'gejala_id' => $gejala['G05'] ?? null, 'cf_pakar' => 0.8],

            // 3. Lalat Bibit (G06, G07, G08)
            ['penyakit_hama_id' => $penyakit['Lalat Bibit'] ?? null, 'gejala_id' => $gejala['G06'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Lalat Bibit'] ?? null, 'gejala_id' => $gejala['G07'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Lalat Bibit'] ?? null, 'gejala_id' => $gejala['G08'] ?? null, 'cf_pakar' => 0.7],

            // 4. Ulat Grayak (G09, G10, G11, G12)
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['G09'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['G10'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['G11'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['G12'] ?? null, 'cf_pakar' => 0.75],

            // 5. Penggerek Batang (G13, G14)
            ['penyakit_hama_id' => $penyakit['Penggerek Batang'] ?? null, 'gejala_id' => $gejala['G13'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang'] ?? null, 'gejala_id' => $gejala['G14'] ?? null, 'cf_pakar' => 0.85],

            // 6. Penggerek Tongkol (G15, G16, G17)
            ['penyakit_hama_id' => $penyakit['Penggerek Tongkol'] ?? null, 'gejala_id' => $gejala['G15'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Penggerek Tongkol'] ?? null, 'gejala_id' => $gejala['G16'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Penggerek Tongkol'] ?? null, 'gejala_id' => $gejala['G17'] ?? null, 'cf_pakar' => 0.85],

            // 7. Penyakit Bulai (G18, G19, G08)
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'] ?? null, 'gejala_id' => $gejala['G18'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'] ?? null, 'gejala_id' => $gejala['G19'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Penyakit Bulai'] ?? null, 'gejala_id' => $gejala['G08'] ?? null, 'cf_pakar' => 0.6],

            // 8. Hawar Daun (G20, G21, G22)
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G20'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G21'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G22'] ?? null, 'cf_pakar' => 0.75],

            // 9. Karat Daun (G23, G24, G25)
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G23'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G24'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G25'] ?? null, 'cf_pakar' => 0.85],

            // 10. Penyakit Gosong (G26, G27)
            ['penyakit_hama_id' => $penyakit['Penyakit Gosong'] ?? null, 'gejala_id' => $gejala['G26'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Penyakit Gosong'] ?? null, 'gejala_id' => $gejala['G27'] ?? null, 'cf_pakar' => 0.95],

            // 11. Virus Mosaik Kerdil Jagung (G28, G29, G30, G31, G32, G33)
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G28'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G29'] ?? null, 'cf_pakar' => 0.9],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G30'] ?? null, 'cf_pakar' => 0.75],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G31'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G32'] ?? null, 'cf_pakar' => 0.8],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Kerdil Jagung'] ?? null, 'gejala_id' => $gejala['G33'] ?? null, 'cf_pakar' => 0.9],
        ];

        // 4. MASUKKAN DATA KE DATABASE
        foreach ($data as $row) {
            if ($row['penyakit_hama_id'] !== null && $row['gejala_id'] !== null) {
                DB::table('basis_penyakit_gejala')->insert([
                    'penyakit_hama_id' => $row['penyakit_hama_id'],
                    'gejala_id'        => $row['gejala_id'],
                    'cf_pakar'         => $row['cf_pakar'],
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}