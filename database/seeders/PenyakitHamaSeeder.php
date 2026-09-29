<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitHamaSeeder extends Seeder
{
    public function run()
    {
        $penyakit = [
            // Hama
            [
                'nama_penyakit' => 'Hama Uret',
                'jenis' => 'hama',
                'deskripsi' => 'Serangan hama uret yang menyerang akar dan bagian bawah tanaman.',
                'solusi' => 'Lakukan pengolahan tanah yang baik dan gunakan insektisida tanah jika diperlukan.',
            ],
            [
                'nama_penyakit' => 'Ulat Tanah',
                'jenis' => 'hama',
                'deskripsi' => 'Larva menyerang pangkal batang tanaman muda pada malam hari.',
                'solusi' => 'Sanitasi lahan, pembersihan gulma, dan penggunaan insektisida pengendali ulat tanah.',
            ],
            [
                'nama_penyakit' => 'Lalat Bibit',
                'jenis' => 'hama',
                'deskripsi' => 'Hama yang menyerang fase awal pertumbuhan tanaman jagung (daun muda).',
                'solusi' => 'Perlakuan benih (*seed treatment*) menggunakan insektisida berbahan aktif fipronil atau imidakloprid.',
            ],
            [
                'nama_penyakit' => 'Ulat Grayak',
                'jenis' => 'hama',
                'deskripsi' => 'Serangan Spodoptera frugiperda, larva memakan daun hingga transparan dan merusak titik tumbuh.',
                'solusi' => 'Insektisida emamektin benzoat, sanitasi lahan, dan pemanfaatan musuh alami.',
            ],
            [
                'nama_penyakit' => 'Penggerek Batang',
                'jenis' => 'hama',
                'deskripsi' => 'Larva Ostrinia furnacalis mengebor batang dan membuat lubang gorokan.',
                'solusi' => 'Insektisida sistemik, potong bagian tanaman terserang, dan pergiliran tanaman.',
            ],
            [
                'nama_penyakit' => 'Penggerek Tongkol',
                'jenis' => 'hama',
                'deskripsi' => 'Hama yang menyerang bagian pangkal hingga dalam tongkol jagung.',
                'solusi' => 'Penyemprotan insektisida selektif dan panen tepat waktu.',
            ],

            // Penyakit
            [
                'nama_penyakit' => 'Penyakit Bulai',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan oleh Peronosclerospora maydis, ditandai garis kuning sejajar tulang daun.',
                'solusi' => 'Fungisida berbahan aktif metalaksil, gunakan varietas tahan, dan atur jarak tanam.',
            ],
            [
                'nama_penyakit' => 'Hawar Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan oleh jamur yang menimbulkan bercak coklat keabuan seperti jerami.',
                'solusi' => 'Fungisida mancozeb, sanitasi sisa tanaman, dan penanaman varietas tahan.',
            ],
            [
                'nama_penyakit' => 'Karat Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan oleh Puccinia sorghi, memunculkan bintik kecil kecoklatan pada daun.',
                'solusi' => 'Fungisida propikonazol, varietas tahan, serta menjaga drainase lahan.',
            ],
            [
                'nama_penyakit' => 'Penyakit Gosong',
                'jenis' => 'penyakit',
                'deskripsi' => 'Menyebabkan pembengkakan (gall) pada bagian tongkol jagung.',
                'solusi' => 'Memusnahkan bagian tanaman yang terserang dan rotasi tanaman.',
            ],
            [
                'nama_penyakit' => 'Virus Mosaik Kerdil Jagung',
                'jenis' => 'penyakit',
                'deskripsi' => 'Infeksi virus yang menyebabkan daun sempit/kaku, batang terpelintir, dan gangguan pertumbuhan.',
                'solusi' => 'Pengendalian vektor kutu daun (vektor virus) dan penggunaan benih sehat yang tahan virus.',
            ],
            [
                'nama_penyakit' => 'Busuk Tongkol',
                'jenis' => 'penyakit',
                'deskripsi' => 'Penyakit yang menyebabkan busuk pada tongkol jagung.',
                'solusi' => 'Penggunaan varietas tahan, sanitasi lahan, dan penggunaan fungisida.',
            ],
            [
                'nama_penyakit' => 'Busuk Batang',
                'jenis' => 'penyakit',
                'deskripsi' => 'Penyakit yang menyebabkan busuk pada batang jagung.',
                'solusi' => 'Penggunaan varietas tahan, sanitasi lahan, dan penggunaan fungisida.',
            ],
        ];

        foreach ($penyakit as $p) {
            DB::table('penyakit_hama')->updateOrInsert(
                ['nama_penyakit' => $p['nama_penyakit']],
                [
                    'jenis'      => $p['jenis'],
                    'deskripsi'  => $p['deskripsi'],
                    'solusi'     => $p['solusi'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}