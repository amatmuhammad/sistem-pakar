<?php

namespace Database\Seeders;

use App\Models\FiturKasusCbr;
use App\Models\HasilDiagnosisCbr;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KasusCbrSeeder extends Seeder
{
    public function run()
    {
        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $p = PenyakitHama::pluck('id', 'nama_penyakit');

        // Kasus 1: Gejala mirip Ulat Grayak (4 dari 6)
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDays(3), 'status_retain' => 'divalidasi'
        ]);
        foreach (['G01','G02','G03','G04'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Ulat Grayak'],
            'similarity_final' => 4/6,
        ]);

        // Kasus 2: Gejala Penggerek Batang (3 dari 6) + tambahan gejala lain
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDays(1), 'status_retain' => 'baru'
        ]);
        foreach (['G07','G08','G10'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Penggerek Batang Jagung'],
            'similarity_final' => 3/6,
        ]);

        // Kasus 3: Campuran gejala bulai dan hawar daun
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDay(), 'status_retain' => 'baru'
        ]);
        foreach (['G13','G14','G18','G16'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        // similarity bisa dihitung oleh sistem, seeder kasih placeholder
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Penyakit Bulai'], // kemiripan tertinggi ke bulai
            'similarity_final' => 3/4, // 3 cocok dari 4 basis bulai (G13,G14,G18)
        ]);
    }
}