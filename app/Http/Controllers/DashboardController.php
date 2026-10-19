<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Jumlah hari yang ditampilkan pada grafik "Total Diagnosa per Tanggal".
     */
    private const PERIODE_HARI = 7;

    /**
     * Jumlah penyakit/hama yang ditampilkan pada grafik ranking.
     */
    private const LIMIT_RANKING = 8;

    public function index()
    {
        // Satu proses diagnosa menghasilkan tepat satu kasus CBR (dan satu kasus CF),
        // sehingga jumlah kasus = jumlah diagnosa. Cukup hitung sekali lalu dipakai dua kali.
        $totalKasus = KasusCbr::count();

        return view('dashboard.index', [
            'totalGejala'        => Gejala::count(),
            'totalPenyakit'      => PenyakitHama::count(),
            'totalKasus'         => $totalKasus,
            'totalDiagnosa'      => $totalKasus,
            'diagnosaPerTanggal' => $this->diagnosaPerTanggal(),
            'penyakitTerbanyak'  => $this->penyakitTerbanyak(),
        ]);
    }

    /**
     * Jumlah proses diagnosa pada beberapa hari terakhir.
     *
     * Tanggal yang dipakai adalah `kasus_cbr.tanggal` (tanggal diagnosa), bukan
     * `created_at` (waktu baris dibuat), agar konsisten dengan halaman Riwayat
     * dan Perbandingan dan tetap akurat untuk data lama/imported.
     *
     * @return array{labels: array<int, string>, total: array<int, int>}
     */
    private function diagnosaPerTanggal(): array
    {
        $periode = $this->periodeTerakhir();

        $jumlahPerTanggal = KasusCbr::query()
            // Alias memakai nama berbeda dari kolom "tanggal" agar GROUP BY
            // tidak ikut mengelompokkan berdasarkan nilai datetime-mentah.
            ->selectRaw('DATE(tanggal) as tanggal_diagnosa, COUNT(*) as total')
            ->whereBetween('tanggal', [
                $periode->first()->copy()->startOfDay(),
                $periode->last()->copy()->endOfDay(),
            ])
            ->groupBy('tanggal_diagnosa')
            ->pluck('total', 'tanggal_diagnosa');

        return [
            'labels' => $periode->map(fn (Carbon $tanggal) => $tanggal->format('d M'))->all(),
            'total'  => $periode
                ->map(fn (Carbon $tanggal) => (int) ($jumlahPerTanggal[$tanggal->format('Y-m-d')] ?? 0))
                ->all(),
        ];
    }

    /**
     * Ranking penyakit/hama yang paling sering terdeteksi pada hasil diagnosa CBR.
     *
     * @return array{labels: array<int, string>, total: array<int, int>}
     */
    private function penyakitTerbanyak(): array
    {
        $ranking = PenyakitHama::query()
            ->withCount('hasilCbr')
            ->whereHas('hasilCbr')
            ->orderByDesc('hasil_cbr_count')
            ->limit(self::LIMIT_RANKING)
            ->get(['nama_penyakit']);

        return [
            'labels' => $ranking->pluck('nama_penyakit')->all(),
            'total'  => $ranking->map(fn (PenyakitHama $penyakit) => (int) $penyakit->hasil_cbr_count)->all(),
        ];
    }

    /**
     * Deret tanggal periode "PERIODE_HARI hari terakhir", dari yang terlama ke yang terbaru.
     *
     * @return Collection<int, Carbon>
     */
    private function periodeTerakhir(): Collection
    {
        $hariIni = Carbon::now()->startOfDay();

        return collect(range(self::PERIODE_HARI - 1, 0))
            ->map(fn (int $offset) => $hariIni->copy()->subDays($offset));
    }
}
