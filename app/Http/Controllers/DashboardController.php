<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Illuminate\Support\Facades\DB;
use App\Models\HasilDiagnosisCf;
use App\Models\HasilDiagnosisCbr;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalGejala' => Gejala::count(),
            'totalPenyakit' => PenyakitHama::count(),
            'totalKasus' => KasusCbr::count(),
            'totalDiagnosa' => HasilDiagnosisCbr::count() + HasilDiagnosisCf::count(),
            'diagnosaPerTanggal' => $this->diagnosaPerTanggal(),
            'penyakitTerbanyak' => $this->penyakitTerbanyak(),
        ]);
    }

    private function diagnosaPerTanggal(): array
    {
        // 1. Buat rentang 7 hari terakhir secara dinamis
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $cbr = HasilDiagnosisCbr::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $labels = [];

        $total = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M'); // Contoh: 16 Jul

            $labels[] = $displayDate;
            // Satu proses diagnosa menghasilkan kasus CBR & CF, jadi gunakan jumlah kasus CBR (sama dengan CF)
            $total[] = (int) ($cbr[$formattedDate] ?? 0);
        }

        return [
            'labels' => $labels,
            'total'  => $total,
        ];
    }

    private function penyakitTerbanyak(): array
    {
        // Gabungkan ranking dari tabel CBR dan CF menjadi satu
        $cbr = $this->rankingPenyakit('hasil_diagnosis_cbr');
        $cf = $this->rankingPenyakit('hasil_diagnosis_cf');

        $labels = $cbr->pluck('nama_penyakit')->merge($cf->pluck('nama_penyakit'))->unique()->values();

        $totals = $labels->map(fn ($item) =>
            (int) optional($cbr->firstWhere('nama_penyakit', $item))->total +
            (int) optional($cf->firstWhere('nama_penyakit', $item))->total
        );

        // Urutkan berdasarkan total diagnosa terbanyak, ambil 8 teratas
        $combined = $labels->map(fn ($item, $i) => ['nama' => $item, 'total' => $totals[$i]])
            ->sortByDesc('total')
            ->take(8)
            ->values();

        return [
            'labels' => $combined->pluck('nama'),
            'total'  => $combined->pluck('total'),
        ];
    }

    private function rankingPenyakit(string $table)
    {
        // Tentukan foreign key yang sesuai berdasarkan tabel yang dilewatkan
        $foreignKey = 'penyakit_hama_id';

        return DB::table($table)
            ->join('penyakit_hama', "{$table}.{$foreignKey}", '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
    }
}