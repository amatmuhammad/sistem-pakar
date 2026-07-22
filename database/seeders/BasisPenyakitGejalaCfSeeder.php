<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasisPenyakitGejalaCfSeeder extends Seeder
{
    public function run()
    {
        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $penyakit = DB::table('penyakit_hama')->pluck('id', 'nama_penyakit');

        // Data cf_pakar untuk semua pasangan penyakit-gejala
        $data = [
            // ===================== ULAT GRAYAK (6 gejala) =====================
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G01', 'cf_pakar' => 0.8],
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G02', 'cf_pakar' => 0.7],
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G03', 'cf_pakar' => 0.9],
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G04', 'cf_pakar' => 0.8],
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G05', 'cf_pakar' => 0.6],
            ['penyakit' => 'Ulat Grayak', 'kode' => 'G06', 'cf_pakar' => 0.5],

            // ===================== PENGGEREK BATANG JAGUNG (6 gejala) =====================
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G07', 'cf_pakar' => 0.9],
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G08', 'cf_pakar' => 0.9],
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G09', 'cf_pakar' => 0.8],
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G10', 'cf_pakar' => 0.6],
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G11', 'cf_pakar' => 0.5],
            ['penyakit' => 'Penggerek Batang Jagung', 'kode' => 'G12', 'cf_pakar' => 0.7],

            // ===================== PENYAKIT BULAI (4 gejala) =====================
            ['penyakit' => 'Penyakit Bulai', 'kode' => 'G13', 'cf_pakar' => 0.8],
            ['penyakit' => 'Penyakit Bulai', 'kode' => 'G14', 'cf_pakar' => 0.7],
            ['penyakit' => 'Penyakit Bulai', 'kode' => 'G18', 'cf_pakar' => 0.8],
            ['penyakit' => 'Penyakit Bulai', 'kode' => 'G11', 'cf_pakar' => 0.6],

            // ===================== HAWAR DAUN (4 gejala) =====================
            ['penyakit' => 'Hawar Daun', 'kode' => 'G16', 'cf_pakar' => 0.9],
            ['penyakit' => 'Hawar Daun', 'kode' => 'G17', 'cf_pakar' => 0.9],
            ['penyakit' => 'Hawar Daun', 'kode' => 'G18', 'cf_pakar' => 0.7],
            ['penyakit' => 'Hawar Daun', 'kode' => 'G10', 'cf_pakar' => 0.5],

            // ===================== KARAT DAUN (3 gejala) =====================
            ['penyakit' => 'Karat Daun', 'kode' => 'G25', 'cf_pakar' => 0.9],
            ['penyakit' => 'Karat Daun', 'kode' => 'G13', 'cf_pakar' => 0.7],
            ['penyakit' => 'Karat Daun', 'kode' => 'G18', 'cf_pakar' => 0.6],

            // ===================== BUSUK TONGKOL (4 gejala) =====================
            ['penyakit' => 'Busuk Tongkol', 'kode' => 'G20', 'cf_pakar' => 0.9],
            ['penyakit' => 'Busuk Tongkol', 'kode' => 'G21', 'cf_pakar' => 0.9],
            ['penyakit' => 'Busuk Tongkol', 'kode' => 'G24', 'cf_pakar' => 0.8],
            ['penyakit' => 'Busuk Tongkol', 'kode' => 'G12', 'cf_pakar' => 0.6],

            // ===================== BUSUK BATANG (4 gejala) =====================
            ['penyakit' => 'Busuk Batang', 'kode' => 'G19', 'cf_pakar' => 0.9],
            ['penyakit' => 'Busuk Batang', 'kode' => 'G22', 'cf_pakar' => 0.8],
            ['penyakit' => 'Busuk Batang', 'kode' => 'G23', 'cf_pakar' => 0.8],
            ['penyakit' => 'Busuk Batang', 'kode' => 'G10', 'cf_pakar' => 0.6],

            // ===================== EMBUN TEPUNG (4 gejala) =====================
            ['penyakit' => 'Penyakit Embun Tepung', 'kode' => 'G15', 'cf_pakar' => 0.9],
            ['penyakit' => 'Penyakit Embun Tepung', 'kode' => 'G13', 'cf_pakar' => 0.6],
            ['penyakit' => 'Penyakit Embun Tepung', 'kode' => 'G14', 'cf_pakar' => 0.6],
            ['penyakit' => 'Penyakit Embun Tepung', 'kode' => 'G30', 'cf_pakar' => 0.7],
        ];

        // Update atau insert data
        foreach ($data as $row) {
            DB::table('basis_penyakit_gejala')->updateOrInsert(
                [
                    'penyakit_hama_id' => $penyakit[$row['penyakit']],
                    'gejala_id'        => $gejala[$row['kode']],
                ],
                [
                    'cf_pakar'   => $row['cf_pakar'],
                    'updated_at' => now(),
                ]
            );
        }

        echo "✅ Semua data cf_pakar berhasil diperbarui.\n";
    }
}