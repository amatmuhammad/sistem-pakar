<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run()
    {
        $gejala = [
            // Hama Uret
            ['kode_gejala' => 'G01', 'nama_gejala' => 'Tanaman layu', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G02', 'nama_gejala' => 'Tanaman mati', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G03', 'nama_gejala' => 'Ditemukan ulat putih besar di dalam tanah', 'bobot_cbr' => 2.0],

            // Ulat Tanah
            ['kode_gejala' => 'G04', 'nama_gejala' => 'Tanaman tiba-tiba rebah', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G05', 'nama_gejala' => 'Bekas gigitan pada batang', 'bobot_cbr' => 1.8],

            // Lalat Bibit
            ['kode_gejala' => 'G06', 'nama_gejala' => 'Pertumbuhan terhambat', 'bobot_cbr' => 1.2],
            ['kode_gejala' => 'G07', 'nama_gejala' => 'Menyerang daun muda', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G08', 'nama_gejala' => 'Tongkol tidak terbentuk', 'bobot_cbr' => 1.8],

            // Ulat Grayak
            ['kode_gejala' => 'G09', 'nama_gejala' => 'Bekas gigitan pada daun', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G10', 'nama_gejala' => 'Daun transparan (tinggal tulang daun)', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G11', 'nama_gejala' => 'Lubang kecil pada daun', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G12', 'nama_gejala' => 'Daun sobek', 'bobot_cbr' => 1.5],

            // Penggerek Batang
            ['kode_gejala' => 'G13', 'nama_gejala' => 'Lubang gorokan pada batang', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G14', 'nama_gejala' => 'Batang/tassel mudah patah', 'bobot_cbr' => 1.8],

            // Penggerek Tongkol
            ['kode_gejala' => 'G15', 'nama_gejala' => 'Lubang pada pangkal tongkol', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G16', 'nama_gejala' => 'Ada pupa/ulat di dalam tongkol', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G17', 'nama_gejala' => 'Tongkol abnormal', 'bobot_cbr' => 1.8],

            // Penyakit Bulai
            ['kode_gejala' => 'G18', 'nama_gejala' => 'Garis putih-kuning sejajar tulang daun', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G19', 'nama_gejala' => 'Spora seperti tepung putih', 'bobot_cbr' => 2.0],

            // Hawar Daun
            ['kode_gejala' => 'G20', 'nama_gejala' => 'Bercak coklat kelabu seperti jerami', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G21', 'nama_gejala' => 'Bercak sejajar tulang daun', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G22', 'nama_gejala' => 'Daun mengering', 'bobot_cbr' => 1.5],

            // Karat Daun
            ['kode_gejala' => 'G23', 'nama_gejala' => 'Bintik kecil pada daun', 'bobot_cbr' => 1.2],
            ['kode_gejala' => 'G24', 'nama_gejala' => 'Bintik coklat kemerahan', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G25', 'nama_gejala' => 'Bintik hitam kecoklatan', 'bobot_cbr' => 1.8],

            // Penyakit Gosong
            ['kode_gejala' => 'G26', 'nama_gejala' => 'Pembengkakan/gall pada tongkol', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'G27', 'nama_gejala' => 'Gall berubah warna gelap', 'bobot_cbr' => 1.8],

            // Virus Mosaik Kerdil Jagung
            ['kode_gejala' => 'G28', 'nama_gejala' => 'Daun sempit dan kaku', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G29', 'nama_gejala' => 'Terbentuk anakan lebih', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G30', 'nama_gejala' => 'Batang terpelintir', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G31', 'nama_gejala' => 'Warna hijau muda & tua', 'bobot_cbr' => 1.2],
            ['kode_gejala' => 'G32', 'nama_gejala' => 'Hijau muda sejajar tulang daun', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G33', 'nama_gejala' => 'Ukuran tongkol berkurang', 'bobot_cbr' => 1.5],
        ];

        foreach ($gejala as $g) {
            DB::table('gejala')->updateOrInsert(
                ['kode_gejala' => $g['kode_gejala']],
                [
                    'nama_gejala' => $g['nama_gejala'],
                    'bobot_cbr'   => $g['bobot_cbr'],
                    'updated_at'  => now(),
                ]
            );
        }
    }
}