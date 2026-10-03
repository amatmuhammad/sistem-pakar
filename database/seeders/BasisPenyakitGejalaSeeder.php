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
            // ================= KATEGORI PENYAKIT =================

            // 1. Penyakit Bulai (P01) - Gejala: G01, G02, G03
            ['penyakit_hama_id' => $penyakit['Bulai'] ?? null, 'gejala_id' => $gejala['G01'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Bulai'] ?? null, 'gejala_id' => $gejala['G02'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Bulai'] ?? null, 'gejala_id' => $gejala['G03'] ?? null, 'cf_pakar' => 0.85],

            // 2. Hawar Daun (P02) - Gejala: G04, G05, G06
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G04'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G05'] ?? null, 'cf_pakar' => 0.75],
            ['penyakit_hama_id' => $penyakit['Hawar Daun'] ?? null, 'gejala_id' => $gejala['G06'] ?? null, 'cf_pakar' => 0.80],

            // 3. Karat Daun (P03) - Gejala: G07, G08, G09
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G07'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G08'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Karat Daun'] ?? null, 'gejala_id' => $gejala['G09'] ?? null, 'cf_pakar' => 0.85],

            // 4. Busuk Batang (P04) - Gejala: G10, G11, G12
            ['penyakit_hama_id' => $penyakit['Busuk Batang'] ?? null, 'gejala_id' => $gejala['G10'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Busuk Batang'] ?? null, 'gejala_id' => $gejala['G11'] ?? null, 'cf_pakar' => 0.75],
            ['penyakit_hama_id' => $penyakit['Busuk Batang'] ?? null, 'gejala_id' => $gejala['G12'] ?? null, 'cf_pakar' => 0.90],

            // 5. Busuk Tongkol (P05) - Gejala: G13, G14, G15
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'] ?? null, 'gejala_id' => $gejala['G13'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'] ?? null, 'gejala_id' => $gejala['G14'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Busuk Tongkol'] ?? null, 'gejala_id' => $gejala['G15'] ?? null, 'cf_pakar' => 0.75],

            // 6. Bercak Daun (P06) - Gejala: G16, G17, G18
            ['penyakit_hama_id' => $penyakit['Bercak Daun'] ?? null, 'gejala_id' => $gejala['G16'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Bercak Daun'] ?? null, 'gejala_id' => $gejala['G17'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Bercak Daun'] ?? null, 'gejala_id' => $gejala['G18'] ?? null, 'cf_pakar' => 0.80],

            // 7. Layu Fusarium (P07) - Gejala: G19, G20, G21
            ['penyakit_hama_id' => $penyakit['Layu Fusarium'] ?? null, 'gejala_id' => $gejala['G19'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Layu Fusarium'] ?? null, 'gejala_id' => $gejala['G20'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Layu Fusarium'] ?? null, 'gejala_id' => $gejala['G21'] ?? null, 'cf_pakar' => 0.75],

            // 8. Virus Mosaik Jagung (P08) - Gejala: G22, G23, G24
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Jagung'] ?? null, 'gejala_id' => $gejala['G22'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Jagung'] ?? null, 'gejala_id' => $gejala['G23'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Virus Mosaik Jagung'] ?? null, 'gejala_id' => $gejala['G24'] ?? null, 'cf_pakar' => 0.80],

            // 9. Busuk Akar (P09) - Gejala: G25, G26, G27
            ['penyakit_hama_id' => $penyakit['Busuk Akar'] ?? null, 'gejala_id' => $gejala['G25'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Busuk Akar'] ?? null, 'gejala_id' => $gejala['G26'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Busuk Akar'] ?? null, 'gejala_id' => $gejala['G27'] ?? null, 'cf_pakar' => 0.75],

            // 10. Antraknose (P10) - Gejala: G28, G29, G30
            ['penyakit_hama_id' => $penyakit['Antraknose'] ?? null, 'gejala_id' => $gejala['G28'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Antraknose'] ?? null, 'gejala_id' => $gejala['G29'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Antraknose'] ?? null, 'gejala_id' => $gejala['G30'] ?? null, 'cf_pakar' => 0.80],

            // ================= KATEGORI HAMA =================

            // 11. Ulat Grayak (H01) - Gejala: GH01, GH02, GH03
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['GH01'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['GH02'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Ulat Grayak'] ?? null, 'gejala_id' => $gejala['GH03'] ?? null, 'cf_pakar' => 0.80],

            // 12. Penggerek Batang (H02) - Gejala: GH04, GH05, GH06
            ['penyakit_hama_id' => $penyakit['Penggerek Batang'] ?? null, 'gejala_id' => $gejala['GH04'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang'] ?? null, 'gejala_id' => $gejala['GH05'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Penggerek Batang'] ?? null, 'gejala_id' => $gejala['GH06'] ?? null, 'cf_pakar' => 0.75],

            // 13. Kutu Daun (H03) - Gejala: GH07, GH08, GH09
            ['penyakit_hama_id' => $penyakit['Kutu Daun'] ?? null, 'gejala_id' => $gejala['GH07'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Kutu Daun'] ?? null, 'gejala_id' => $gejala['GH08'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Kutu Daun'] ?? null, 'gejala_id' => $gejala['GH09'] ?? null, 'cf_pakar' => 0.90],

            // 14. Belalang (H04) - Gejala: GH10, GH11, GH12
            ['penyakit_hama_id' => $penyakit['Belalang'] ?? null, 'gejala_id' => $gejala['GH10'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Belalang'] ?? null, 'gejala_id' => $gejala['GH11'] ?? null, 'cf_pakar' => 0.80],
            ['penyakit_hama_id' => $penyakit['Belalang'] ?? null, 'gejala_id' => $gejala['GH12'] ?? null, 'cf_pakar' => 0.75],

            // 15. Tikus Sawah (H05) - Gejala: GH13, GH14, GH15
            ['penyakit_hama_id' => $penyakit['Tikus Sawah'] ?? null, 'gejala_id' => $gejala['GH13'] ?? null, 'cf_pakar' => 0.90],
            ['penyakit_hama_id' => $penyakit['Tikus Sawah'] ?? null, 'gejala_id' => $gejala['GH14'] ?? null, 'cf_pakar' => 0.85],
            ['penyakit_hama_id' => $penyakit['Tikus Sawah'] ?? null, 'gejala_id' => $gejala['GH15'] ?? null, 'cf_pakar' => 0.80],
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
