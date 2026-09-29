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

        // Kasus 1: Gejala Ulat Grayak (Menggunakan G09, G10, G11, G12)
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDays(3), 'status_retain' => 'divalidasi'
        ]);
        foreach (['G09','G10','G11','G12'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Ulat Grayak'],
            'similarity_final' => 4/4,
        ]);

        // Kasus 2: Gejala Penggerek Batang (Menggunakan G13, G14)
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDays(1), 'status_retain' => 'baru'
        ]);
        foreach (['G13','G14'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Penggerek Batang'],
            'similarity_final' => 2/2,
        ]);

        // Kasus 3: Gejala Penyakit Bulai (Menggunakan G18, G19)
        $kasus = KasusCbr::create([
            'user_id' => null, 'tanggal' => now()->subDay(), 'status_retain' => 'baru'
        ]);
        foreach (['G18','G19'] as $kode) {
            FiturKasusCbr::create(['kasus_cbr_id' => $kasus->id, 'gejala_id' => $gejala[$kode], 'nilai' => 1]);
        }
        HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $p['Penyakit Bulai'],
            'similarity_final' => 2/2,
        ]);
    }
}