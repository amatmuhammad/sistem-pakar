<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitHamaSeeder extends Seeder
{
    public function run()
    {
        $penyakit = [
            [
                'nama_penyakit' => 'Ulat Grayak',
                'jenis' => 'hama',
                'deskripsi' => 'Serangan Spodoptera frugiperda, larva memakan daun dan titik tumbuh.',
                'solusi' => 'Insektisida emamektin benzoat, sanitasi lahan, musuh alami.',
            ],
            [
                'nama_penyakit' => 'Penggerek Batang Jagung',
                'jenis' => 'hama',
                'deskripsi' => 'Ostrinia furnacalis mengebor batang dan tongkol, menyebabkan layu dan patah.',
                'solusi' => 'Insektisida sistemik, potong tanaman terserang, pergiliran tanaman.',
            ],
            [
                'nama_penyakit' => 'Penyakit Bulai',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan Peronosclerospora maydis, ditandai bercak kuning dan daun menggulung.',
                'solusi' => 'Fungisida metalaksil, gunakan varietas tahan, atur jarak tanam.',
            ],
            [
                'nama_penyakit' => 'Hawar Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan Helminthosporium maydis, bercak coklat keabuan dengan tepi kuning.',
                'solusi' => 'Fungisida mancozeb, sanitasi sisa tanaman, varietas tahan.',
            ],
            [
                'nama_penyakit' => 'Karat Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Puccinia sorghi menyebabkan bercak karat oranye pada daun.',
                'solusi' => 'Fungisida propikonazol, tanam varietas tahan, drainase baik.',
            ],
            [
                'nama_penyakit' => 'Busuk Tongkol',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan Fusarium/Giberella, tongkol berlendir, biji merah jambu.',
                'solusi' => 'Fungisida benih, panen tepat waktu, penyimpanan kering.',
            ],
            [
                'nama_penyakit' => 'Busuk Batang',
                'jenis' => 'penyakit',
                'deskripsi' => 'Pythium atau Fusarium menyebabkan busuk pangkal batang dan akar.',
                'solusi' => 'Fungisida benih, drainase baik, hindari luka akar.',
            ],
            [
                'nama_penyakit' => 'Penyakit Embun Tepung',
                'jenis' => 'penyakit',
                'deskripsi' => 'Erysiphe graminis, permukaan daun dilapisi tepung putih.',
                'solusi' => 'Fungisida sulfur, jaga kelembaban, varietas toleran.',
            ],
        ];

        DB::table('penyakit_hama')->insert($penyakit);
    }
}