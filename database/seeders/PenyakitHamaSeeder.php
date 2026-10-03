<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitHamaSeeder extends Seeder
{
    public function run()
    {
        $penyakit = [
            // ================= KATEGORI HAMA (H01 - H05) =================
            [
                'nama_penyakit' => 'Ulat Grayak',
                'jenis' => 'hama',
                'deskripsi' => 'Serangan Spodoptera frugiperda, larva memakan daun hingga transparan dan merusak titik tumbuh tanaman jagung.',
                'solusi' => 'Gunakan insektisida sesuai dosis, lakukan pengendalian hama terpadu, dan bersihkan area sekitar tanaman.',
            ],
            [
                'nama_penyakit' => 'Penggerek Batang',
                'jenis' => 'hama',
                'deskripsi' => 'Larva Ostrinia furnacalis mengebor batang dan membuat lubang gorokan yang menyebabkan tanaman mudah patah.',
                'solusi' => 'Gunakan varietas tahan hama, semprot insektisida secara rutin, dan lakukan sanitasi lahan sekitar.',
            ],
            [
                'nama_penyakit' => 'Kutu Daun',
                'jenis' => 'hama',
                'deskripsi' => 'Hama yang menyebabkan daun menggulung, permukaan daun lengket, dan tanaman tampak layu.',
                'solusi' => 'Gunakan pestisida tertentu, kendalikan semut di sekitar, dan semprot insektisida sesuai kebutuhan.',
            ],
            [
                'nama_penyakit' => 'Belalang',
                'jenis' => 'hama',
                'deskripsi' => 'Hama yang memakan habis daun jagung dan meninggalkan bekas gigitan pada daun.',
                'solusi' => 'Gunakan perangkap umpan, lakukan pengendalian manual, dan bersihkan gulma sekitar lahan.',
            ],
            [
                'nama_penyakit' => 'Tikus Sawah',
                'jenis' => 'hama',
                'deskripsi' => 'Hama yang memotong batang tanaman, merusak atau menghilangkan jagung, dan meninggalkan jejak gigitan.',
                'solusi' => 'Gunakan perangkap tikus, lakukan gropyokan tikus, dan jaga kebersihan lahan.',
            ],

            // ================= KATEGORI PENYAKIT (P01 - P10) =================
            [
                'nama_penyakit' => 'Bulai',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan oleh Peronosclerospora maydis, ditandai daun berwarna putih kehijauan, pertumbuhan terhambat, dan daun menjadi kaku tegak.',
                'solusi' => 'Gunakan benih tahan penyakit, cabut tanaman yang terinfeksi, dan lakukan sanitasi lahan.',
            ],
            [
                'nama_penyakit' => 'Hawar Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai bercak coklat memanjang pada daun, ujung daun mengering, dan daun terlihat terbakar.',
                'solusi' => 'Semprot fungisida secara berkala, jaga kelembapan lahan, dan lakukan rotasi tanaman.',
            ],
            [
                'nama_penyakit' => 'Karat Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Disebabkan oleh Puccinia sorghi, memunculkan bercak coklat kekuningan, permukaan daun kasar, dan daun cepat mengering.',
                'solusi' => 'Gunakan varietas tahan penyakit, lakukan penyemprotan fungisida, dan bersihkan gulma sekitar tanaman.',
            ],
            [
                'nama_penyakit' => 'Busuk Batang',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai batang lunak dan busuk, tanaman mudah roboh, serta batang berubah warna kecoklatan.',
                'solusi' => 'Perbaiki drainase tanah, kurangi kepadatan tanaman, dan gunakan benih sehat.',
            ],
            [
                'nama_penyakit' => 'Busuk Tongkol',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai tongkol berjamur, hijau ovul menyebar, dan tongkol berubah warna.',
                'solusi' => 'Panen tepat waktu, simpan hasil panen di tempat kering, dan gunakan fungisida jika diperlukan.',
            ],
            [
                'nama_penyakit' => 'Bercak Daun',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai bercak oval pada daun, daun menguning, dan pertumbuhan daun terganggu.',
                'solusi' => 'Lakukan sanitasi lahan, gunakan fungisida, dan bersihkan sisa tanaman sakit.',
            ],
            [
                'nama_penyakit' => 'Layu Fusarium',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai tanaman layu, akar membusuk, dan pertumbuhan tanaman terhambat.',
                'solusi' => 'Gunakan fungisida sistemik, lakukan rotasi tanaman, dan gunakan bibit tahan penyakit.',
            ],
            [
                'nama_penyakit' => 'Virus Mosaik Jagung',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai daun belang hijau muda-tua, bentuk daun tidak normal, dan pertumbuhan tanaman lambat.',
                'solusi' => 'Kendalikan hama vektor, cabut tanaman terinfeksi, dan gunakan benih sehat.',
            ],
            [
                'nama_penyakit' => 'Busuk Akar',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai akar berwarna coklat kehitaman, tanaman mudah layu, dan pertumbuhan akar terganggu.',
                'solusi' => 'Perbaiki drainase tanah, hindari penanaman berlebih, dan gunakan fungisida.',
            ],
            [
                'nama_penyakit' => 'Antraknose',
                'jenis' => 'penyakit',
                'deskripsi' => 'Ditandai bercak hitam pada daun, batang mengering, dan tanaman mati pucuk/pelepah.',
                'solusi' => 'Gunakan fungisida, lakukan rotasi tanaman, dan gunakan varietas tahan penyakit.',
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
