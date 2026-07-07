<?php

namespace Database\Seeders;

use App\Models\PenyakitHama;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenyakitHamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [

            [
                'nama_penyakit' => 'Bulai Jagung',
                'jenis' => 'penyakit',
                'deskripsi' => 'Penyakit jamur yang menyebabkan klorosis pada daun muda.',
                'solusi' => 'Gunakan benih tahan penyakit dan lakukan rotasi tanaman.'
            ],

            [
                'nama_penyakit' => 'Karat Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai pustul berwarna coklat keemasan pada daun.',
                'solusi' => 'Gunakan fungisida dan jaga kelembaban lahan.'
            ],

            [
                'nama_penyakit' => 'Hawar Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Lesi memanjang pada daun yang menyebabkan daun kering.',
                'solusi' => 'Gunakan varietas tahan dan sanitasi lahan.'
            ],

            [
                'nama_penyakit' => 'Busuk Batang',
                'jenis' => 'penyakit',
                'deskripsi' => 'Batang melunak dan mudah rebah.',
                'solusi' => 'Perbaiki drainase dan hindari kelembaban tinggi.'
            ],

            [
                'nama_penyakit' => 'Virus Mosaik Jagung',
                'jenis' => 'penyakit',
                'deskripsi' => 'Daun menunjukkan pola mosaik dan tanaman kerdil.',
                'solusi' => 'Pengendalian vektor serangga dan penggunaan benih sehat.'
            ],

            [
                'nama_penyakit' => 'Ulat Grayak',
                'jenis' => 'hama',
                'deskripsi' => 'Ulat memakan daun hingga tersisa tulang daun.',
                'solusi' => 'Gunakan insektisida biologis dan pengendalian mekanis.'
            ],

            [
                'nama_penyakit' => 'Penggerek Batang',
                'jenis' => 'hama',
                'deskripsi' => 'Larva menggerek batang hingga tanaman patah.',
                'solusi' => 'Pengendalian hayati dan pemotongan tanaman terserang.'
            ],

            [
                'nama_penyakit' => 'Penggerek Tongkol',
                'jenis' => 'hama',
                'deskripsi' => 'Merusak tongkol dan biji jagung.',
                'solusi' => 'Monitoring awal dan aplikasi insektisida sesuai dosis.'
            ],

            [
                'nama_penyakit' => 'Lalat Bibit',
                'jenis' => 'hama',
                'deskripsi' => 'Menyerang tanaman muda sehingga layu dan mati.',
                'solusi' => 'Perlakuan benih dan sanitasi lahan.'
            ],

            [
                'nama_penyakit' => 'Hama Uret',
                'jenis' => 'hama',
                'deskripsi' => 'Larva memakan akar sehingga tanaman tumbang.',
                'solusi' => 'Pengolahan tanah dan pengendalian biologis.'
            ],
        ];

        foreach ($data as $item) {
            PenyakitHama::create($item);
        }
    }
}
