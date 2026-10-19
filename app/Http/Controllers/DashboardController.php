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
            'totalDiagnosa' => KasusCbr::count(), // satu proses diagnosa = satu kasus CBR (hindari double count dengan CF)
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
        // Hanya hitung dari tabel CBR untuk menghindari double count.
        // Satu proses diagnosa membuat record di kedua tabel CBR dan CF,
        // jadi jika digabung akan masing-masing penyakit terhitung 2 kali.
        $cbr = $this->rankingPenyakit('hasil_diagnosis_cbr');

        return [
            'labels' => $cbr->pluck('nama_penyakit'),
            'total'  => $cbr->pluck('total'),
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