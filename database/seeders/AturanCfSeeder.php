<?php

namespace Database\Seeders;

use App\Models\AturanCf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AturanCfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan data lama agar tidak duplikat saat di-seed ulang
        DB::table('aturan_cf')->delete();

        // 2. Ambil ID berdasarkan Nama dan Kode agar akurat tanpa perlu mapping manual
        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $penyakit = DB::table('penyakit_hama')->pluck('id', 'nama_penyakit');

        // 3. Deklarasi Rules CF Lengkap (MB dan MD)
        $rules = [
            // 1. Hama Uret
            ['penyakit' => 'Hama Uret', 'gejala' => 'G01', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Hama Uret', 'gejala' => 'G02', 'mb' => 0.70, 'md' => 0.10],
            ['penyakit' => 'Hama Uret', 'gejala' => 'G03', 'mb' => 1.00, 'md' => 0.10],

            // 2. Ulat Tanah
            ['penyakit' => 'Ulat Tanah', 'gejala' => 'G01', 'mb' => 0.80, 'md' => 0.10],
            ['penyakit' => 'Ulat Tanah', 'gejala' => 'G02', 'mb' => 0.70, 'md' => 0.10],
            ['penyakit' => 'Ulat Tanah', 'gejala' => 'G04', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Ulat Tanah', 'gejala' => 'G05', 'mb' => 0.90, 'md' => 0.10],

            // 3. Lalat Bibit
            ['penyakit' => 'Lalat Bibit', 'gejala' => 'G06', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Lalat Bibit', 'gejala' => 'G07', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Lalat Bibit', 'gejala' => 'G08', 'mb' => 0.80, 'md' => 0.10],

            // 4. Ulat Grayak
            ['penyakit' => 'Ulat Grayak', 'gejala' => 'G09', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Ulat Grayak', 'gejala' => 'G10', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Ulat Grayak', 'gejala' => 'G11', 'mb' => 0.95, 'md' => 0.10],
            ['penyakit' => 'Ulat Grayak', 'gejala' => 'G12', 'mb' => 0.85, 'md' => 0.10],

            // 5. Penggerek Batang
            ['penyakit' => 'Penggerek Batang', 'gejala' => 'G13', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Penggerek Batang', 'gejala' => 'G14', 'mb' => 0.95, 'md' => 0.10],

            // 6. Penggerek Tongkol
            ['penyakit' => 'Penggerek Tongkol', 'gejala' => 'G15', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Penggerek Tongkol', 'gejala' => 'G16', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Penggerek Tongkol', 'gejala' => 'G17', 'mb' => 0.95, 'md' => 0.10],

            // 7. Penyakit Bulai
            ['penyakit' => 'Penyakit Bulai', 'gejala' => 'G18', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Penyakit Bulai', 'gejala' => 'G19', 'mb' => 0.95, 'md' => 0.10],
            ['penyakit' => 'Penyakit Bulai', 'gejala' => 'G08', 'mb' => 0.70, 'md' => 0.10],

            // 8. Hawar Daun
            ['penyakit' => 'Hawar Daun', 'gejala' => 'G20', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Hawar Daun', 'gejala' => 'G21', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Hawar Daun', 'gejala' => 'G22', 'mb' => 0.85, 'md' => 0.10],

            // 9. Karat Daun
            ['penyakit' => 'Karat Daun', 'gejala' => 'G23', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Karat Daun', 'gejala' => 'G24', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Karat Daun', 'gejala' => 'G25', 'mb' => 0.95, 'md' => 0.10],

            // 10. Penyakit Gosong
            ['penyakit' => 'Penyakit Gosong', 'gejala' => 'G26', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Penyakit Gosong', 'gejala' => 'G27', 'mb' => 1.00, 'md' => 0.05],

            // 11. Virus Mosaik Kerdil Jagung
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G28', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G29', 'mb' => 1.00, 'md' => 0.10],
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G30', 'mb' => 0.85, 'md' => 0.10],
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G31', 'mb' => 0.95, 'md' => 0.10],
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G32', 'mb' => 0.90, 'md' => 0.10],
            ['penyakit' => 'Virus Mosaik Kerdil Jagung', 'gejala' => 'G33', 'mb' => 1.00, 'md' => 0.10],
        ];

        // 4. Masukkan data ke dalam tabel AturanCf
        foreach ($rules as $rule) {
            // Pastikan relasi penyakit dan gejala ditemukan agar tidak terjadi error relasi
            if (isset($penyakit[$rule['penyakit']]) && isset($gejala[$rule['gejala']])) {
                AturanCf::create([
                    'penyakit_id' => $penyakit[$rule['penyakit']],
                    'gejala_id'   => $gejala[$rule['gejala']],
                    'mb'          => $rule['mb'],
                    'md'          => $rule['md']
                ]);
            }
        }
    }
}