<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run()
    {
        $gejala = [
            // ================= GEJALA PENYAKIT (G01 - G30) =================

            // P01 - Bulai
            ['kode_gejala' => 'G01', 'nama_gejala' => 'Daun berwarna putih kehijauan', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G02', 'nama_gejala' => 'Pertumbuhan tanaman terhambat', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G03', 'nama_gejala' => 'Daun menjadi kaku dan tegak', 'bobot_cbr' => 1.6],

            // P02 - Hawar Daun
            ['kode_gejala' => 'G04', 'nama_gejala' => 'Bercak coklat memanjang pada daun', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G05', 'nama_gejala' => 'Ujung daun mengering', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G06', 'nama_gejala' => 'Daun terlihat terbakar', 'bobot_cbr' => 1.7],

            // P03 - Karat Daun
            ['kode_gejala' => 'G07', 'nama_gejala' => 'Muncul bercak coklat kekuningan', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G08', 'nama_gejala' => 'Permukaan daun kasar', 'bobot_cbr' => 1.5],
            ['kode_gejala' => 'G09', 'nama_gejala' => 'Daun cepat mengering', 'bobot_cbr' => 1.7],

            // P04 - Busuk Batang
            ['kode_gejala' => 'G10', 'nama_gejala' => 'Batang lunak dan busuk', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G11', 'nama_gejala' => 'Tanaman mudah roboh', 'bobot_cbr' => 1.6],
            ['kode_gejala' => 'G12', 'nama_gejala' => 'Batang berubah warna kecoklatan', 'bobot_cbr' => 1.9],

            // P05 - Busuk Tongkol
            ['kode_gejala' => 'G13', 'nama_gejala' => 'Tongkol berjamur', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G14', 'nama_gejala' => 'Hijau ovul menyebar', 'bobot_cbr' => 1.6],
            ['kode_gejala' => 'G15', 'nama_gejala' => 'Tongkol berubah warna', 'bobot_cbr' => 1.5],

            // P06 - Bercak Daun
            ['kode_gejala' => 'G16', 'nama_gejala' => 'Bercak oval pada daun', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G17', 'nama_gejala' => 'Daun menguning', 'bobot_cbr' => 1.6],
            ['kode_gejala' => 'G18', 'nama_gejala' => 'Pertumbuhan daun terganggu', 'bobot_cbr' => 1.5],

            // P07 - Layu Fusarium
            ['kode_gejala' => 'G19', 'nama_gejala' => 'Tanaman layu', 'bobot_cbr' => 1.9],
            ['kode_gejala' => 'G20', 'nama_gejala' => 'Akar membusuk', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G21', 'nama_gejala' => 'Pertumbuhan terhambat', 'bobot_cbr' => 1.5],

            // P08 - Virus Mosaik Jagung
            ['kode_gejala' => 'G22', 'nama_gejala' => 'Daun belang hijau muda-tua', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G23', 'nama_gejala' => 'Bentuk daun tidak normal', 'bobot_cbr' => 1.6],
            ['kode_gejala' => 'G24', 'nama_gejala' => 'Pertumbuhan tanaman lambat', 'bobot_cbr' => 1.5],

            // P09 - Busuk Akar
            ['kode_gejala' => 'G25', 'nama_gejala' => 'Akar berwarna coklat kehitaman', 'bobot_cbr' => 1.9],
            ['kode_gejala' => 'G26', 'nama_gejala' => 'Tanaman mudah layu', 'bobot_cbr' => 1.7],
            ['kode_gejala' => 'G27', 'nama_gejala' => 'Pertumbuhan akar terganggu', 'bobot_cbr' => 1.6],

            // P10 - Antraknose
            ['kode_gejala' => 'G28', 'nama_gejala' => 'Bercak hitam pada daun', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'G29', 'nama_gejala' => 'Batang mengering', 'bobot_cbr' => 1.7],
            ['kode_gejala' => 'G30', 'nama_gejala' => 'Tanaman mati pucuk/pelepah', 'bobot_cbr' => 1.9],

            // ================= GEJALA HAMA (GH01 - GH15) =================

            // H01 - Ulat Grayak
            ['kode_gejala' => 'GH01', 'nama_gejala' => 'Daun berlubang tidak beraturan', 'bobot_cbr' => 1.9],
            ['kode_gejala' => 'GH02', 'nama_gejala' => 'Tanaman rusak parah pada malam hari', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'GH03', 'nama_gejala' => 'Terdapat ulat pada daun', 'bobot_cbr' => 2.0],

            // H02 - Penggerek Batang
            ['kode_gejala' => 'GH04', 'nama_gejala' => 'Batang berlubang', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'GH05', 'nama_gejala' => 'Tanaman mudah patah', 'bobot_cbr' => 1.7],
            ['kode_gejala' => 'GH06', 'nama_gejala' => 'Pertumbuhan tanaman terganggu', 'bobot_cbr' => 1.5],

            // H03 - Kutu Daun
            ['kode_gejala' => 'GH07', 'nama_gejala' => 'Daun menggulung', 'bobot_cbr' => 1.7],
            ['kode_gejala' => 'GH08', 'nama_gejala' => 'Permukaan daun lengket', 'bobot_cbr' => 1.6],
            ['kode_gejala' => 'GH09', 'nama_gejala' => 'Tanaman tampak layu', 'bobot_cbr' => 1.8],

            // H04 - Belalang
            ['kode_gejala' => 'GH10', 'nama_gejala' => 'Daun habis dimakan', 'bobot_cbr' => 1.9],
            ['kode_gejala' => 'GH11', 'nama_gejala' => 'Terdapat bekas gigitan pada daun', 'bobot_cbr' => 1.8],
            ['kode_gejala' => 'GH12', 'nama_gejala' => 'Pertumbuhan tanaman terganggu', 'bobot_cbr' => 1.5],

            // H05 - Tikus Sawah
            ['kode_gejala' => 'GH13', 'nama_gejala' => 'Batang tanaman terpotong', 'bobot_cbr' => 2.0],
            ['kode_gejala' => 'GH14', 'nama_gejala' => 'Jagung rusak atau hilang', 'bobot_cbr' => 1.9],
            ['kode_gejala' => 'GH15', 'nama_gejala' => 'Terdapat jejak gigitan', 'bobot_cbr' => 1.8],
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
