<?php

namespace Database\Seeders;

use App\Services\DiagnosaEngine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder riwayat diagnosa demo.
 *
 * Membuat data diagnosa selama 7 hari terakhir (dihitung relatif terhadap waktu
 * seeder dijalankan) supaya grafik "Total Diagnosa per Tanggal" pada Dashboard
 * menampilkan riwayat, bukan hanya data hari ini.
 *
 * Hasil CBR dan CF dihitung memakai DiagnosaEngine, sehingga nilai similarity_final
 * dan cf_final selalu konsisten dengan perhitungan mesin yang dipakai aplikasi.
 *
 * CATATAN: seeder ini mereset tabel hasil diagnosa. Jalankan lewat
 * `php artisan migrate:fresh --seed` agar data lama tidak tertinggal.
 */
class KasusCbrSeeder extends Seeder
{
    public function run()
    {
        $engine = app(DiagnosaEngine::class);

        $gejala = DB::table('gejala')->pluck('id', 'kode_gejala');
        $userId = DB::table('users')->orderBy('id')->value('id');

        $this->resetTabelDiagnosa();

        foreach ($this->riwayatDemo() as $item) {
            $tanggal = Carbon::now()
                ->subDays($item['hari'])
                ->setTime($item['jam'], $item['menit']);

            $gejalaIds = collect($item['gejala'])
                ->map(fn (string $kode) => $gejala[$kode] ?? null)
                ->filter()
                ->values()
                ->all();

            if (empty($gejalaIds)) {
                continue;
            }

            $hasilCbr = $engine->cbr($gejalaIds);
            $hasilCf = $engine->cf($gejalaIds);

            if (! $hasilCbr || ! $hasilCf) {
                continue;
            }

            $this->simpanKasusCbr($gejalaIds, $hasilCbr, $tanggal, $item['status'], $userId);
            $this->simpanKasusCf($gejalaIds, $hasilCf, $tanggal, $userId);
        }
    }

    /**
     * Hapus data diagnosa sebelumnya agar seeder aman dijalankan berulang kali.
     */
    private function resetTabelDiagnosa(): void
    {
        DB::table('hasil_diagnosis_cbr')->delete();
        DB::table('fitur_kasus_cbr')->delete();
        DB::table('kasus_cbr')->delete();
        DB::table('hasil_diagnosis_cf')->delete();
        DB::table('gejala_kasus_cf')->delete();
        DB::table('kasus_cf')->delete();
    }

    /**
     * Simpan sisi CBR: kasus, fitur gejala, dan hasil diagnosis.
     */
    private function simpanKasusCbr(array $gejalaIds, array $hasil, Carbon $tanggal, string $status, $userId): void
    {
        $kasusId = DB::table('kasus_cbr')->insertGetId([
            'user_id'       => $userId,
            'tanggal'       => $tanggal,
            'status_retain' => $status,
            'created_at'    => $tanggal,
            'updated_at'    => $tanggal,
        ], 'id');

        foreach ($gejalaIds as $gejalaId) {
            DB::table('fitur_kasus_cbr')->insert([
                'kasus_cbr_id' => $kasusId,
                'gejala_id'    => $gejalaId,
                'nilai'        => 1,
                'created_at'   => $tanggal,
                'updated_at'   => $tanggal,
            ]);
        }

        DB::table('hasil_diagnosis_cbr')->insert([
            'kasus_cbr_id'     => $kasusId,
            'penyakit_hama_id' => $hasil['penyakit_hama_id'],
            'similarity_final' => $hasil['similarity_final'],
            'divalidasi_pakar' => $status === 'divalidasi',
            'created_at'       => $tanggal,
            'updated_at'       => $tanggal,
        ]);
    }

    /**
     * Simpan sisi CF: kasus, gejala keyakinan, dan hasil diagnosis.
     */
    private function simpanKasusCf(array $gejalaIds, array $hasil, Carbon $tanggal, $userId): void
    {
        $kasusId = DB::table('kasus_cf')->insertGetId([
            'user_id'    => $userId,
            'tanggal'    => $tanggal,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ], 'id');

        foreach ($gejalaIds as $gejalaId) {
            DB::table('gejala_kasus_cf')->insert([
                'kasus_cf_id'   => $kasusId,
                'gejala_id'     => $gejalaId,
                'nilai_cf_user' => DiagnosaEngine::NILAI_CF_USER,
                'created_at'    => $tanggal,
                'updated_at'    => $tanggal,
            ]);
        }

        DB::table('hasil_diagnosis_cf')->insert([
            'kasus_cf_id'      => $kasusId,
            'penyakit_hama_id' => $hasil['penyakit_hama_id'],
            'cf_final'         => $hasil['cf_final'],
            'created_at'       => $tanggal,
            'updated_at'       => $tanggal,
        ]);
    }

    /**
     * Daftar diagnosa demo: waktu (hari ke berapa mundur, jam, menit),
     * kode gejala yang dipilih, dan status retain kasus.
     *
     * Beberapa kasus memakai gejala dari dua penyakit berbeda sehingga metode CBR
     * dan CF dapat menghasilkan kesimpulan berbeda (berguna untuk halaman Perbandingan).
     *
     * @return array<int, array{hari: int, jam: int, menit: int, gejala: array<int, string>, status: string}>
     */
    private function riwayatDemo(): array
    {
        return [
            // 6 hari lalu
            ['hari' => 6, 'jam' => 8, 'menit' => 15, 'gejala' => ['G01', 'G02', 'G03'], 'status' => 'divalidasi'],
            ['hari' => 6, 'jam' => 14, 'menit' => 40, 'gejala' => ['GH01', 'GH02', 'GH03'], 'status' => 'divalidasi'],

            // 5 hari lalu
            ['hari' => 5, 'jam' => 9, 'menit' => 5, 'gejala' => ['G07', 'G08', 'G09'], 'status' => 'divalidasi'],
            ['hari' => 5, 'jam' => 15, 'menit' => 20, 'gejala' => ['GH13', 'GH14', 'GH15'], 'status' => 'divalidasi'],

            // 4 hari lalu
            ['hari' => 4, 'jam' => 8, 'menit' => 45, 'gejala' => ['G01', 'G02'], 'status' => 'baru'],
            ['hari' => 4, 'jam' => 11, 'menit' => 10, 'gejala' => ['G10', 'G11', 'G12'], 'status' => 'baru'],
            ['hari' => 4, 'jam' => 14, 'menit' => 25, 'gejala' => ['GH07', 'GH08', 'GH09'], 'status' => 'divalidasi'],
            // Gejala dari dua penyakit: CBR -> Hawar Daun, CF -> Bulai
            ['hari' => 4, 'jam' => 16, 'menit' => 30, 'gejala' => ['G02', 'G06'], 'status' => 'baru'],

            // 3 hari lalu
            ['hari' => 3, 'jam' => 8, 'menit' => 0, 'gejala' => ['G04', 'G05', 'G06'], 'status' => 'divalidasi'],
            ['hari' => 3, 'jam' => 10, 'menit' => 35, 'gejala' => ['GH04', 'GH05', 'GH06'], 'status' => 'baru'],
            // CBR -> Hawar Daun, CF -> Busuk Batang
            ['hari' => 3, 'jam' => 13, 'menit' => 25, 'gejala' => ['G04', 'G12'], 'status' => 'baru'],

            // 2 hari lalu
            ['hari' => 2, 'jam' => 8, 'menit' => 30, 'gejala' => ['G13', 'G14', 'G15'], 'status' => 'baru'],
            ['hari' => 2, 'jam' => 10, 'menit' => 15, 'gejala' => ['G16', 'G17', 'G18'], 'status' => 'baru'],
            ['hari' => 2, 'jam' => 12, 'menit' => 5, 'gejala' => ['GH10', 'GH11', 'GH12'], 'status' => 'baru'],
            // CBR -> Bulai, CF -> Ulat Grayak (kasus <> hama)
            ['hari' => 2, 'jam' => 17, 'menit' => 10, 'gejala' => ['G01', 'GH01'], 'status' => 'baru'],

            // 1 hari lalu
            ['hari' => 1, 'jam' => 9, 'menit' => 20, 'gejala' => ['G19', 'G20', 'G21'], 'status' => 'divalidasi'],
            ['hari' => 1, 'jam' => 11, 'menit' => 40, 'gejala' => ['G28', 'G29', 'G30'], 'status' => 'baru'],
            ['hari' => 1, 'jam' => 14, 'menit' => 50, 'gejala' => ['GH01', 'GH02'], 'status' => 'baru'],
            // CBR -> Ulat Grayak, CF -> Bulai
            ['hari' => 1, 'jam' => 16, 'menit' => 15, 'gejala' => ['G03', 'GH03'], 'status' => 'baru'],

            // hari ini
            ['hari' => 0, 'jam' => 7, 'menit' => 50, 'gejala' => ['G01', 'G02', 'G03'], 'status' => 'baru'],
            ['hari' => 0, 'jam' => 9, 'menit' => 25, 'gejala' => ['G22', 'G23', 'G24'], 'status' => 'baru'],
            ['hari' => 0, 'jam' => 11, 'menit' => 0, 'gejala' => ['G25', 'G26', 'G27'], 'status' => 'baru'],
            ['hari' => 0, 'jam' => 15, 'menit' => 30, 'gejala' => ['GH13', 'GH14', 'GH15'], 'status' => 'baru'],
        ];
    }
}
