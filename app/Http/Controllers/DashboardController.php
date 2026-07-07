<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Illuminate\Support\Facades\DB;
use App\Models\HasilDiagnosisCf;
use App\Models\HasilDiagnosisCbr;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $diagnosaPerTanggal = $this->diagnosaPerTanggal();
        $penyakitTerbanyak = $this->penyakitTerbanyak();

        return view('dashboard.index', [
            'totalGejala' => Gejala::count(),
            'totalPenyakit' => PenyakitHama::count(),
            'totalKasus' => KasusCbr::count(),
            'totalDiagnosa' => HasilDiagnosisCbr::count() + HasilDiagnosisCf::count(),
            'diagnosaPerTanggal' => $diagnosaPerTanggal,
            'penyakitTerbanyak' => $penyakitTerbanyak,
        ]);
    }

    private function diagnosaPerTanggal(): array
    {
        $cbr = HasilDiagnosisCbr::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $cf = HasilDiagnosisCf::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $tanggal = $cbr->keys()->merge($cf->keys())->unique()->sort()->values();

        return [
            'labels' => $tanggal,
            'cbr' => $tanggal->map(fn ($item) => (int) ($cbr[$item] ?? 0))->values(),
            'cf' => $tanggal->map(fn ($item) => (int) ($cf[$item] ?? 0))->values(),
        ];
    }

    private function penyakitTerbanyak(): array
    {
        $cbr = $this->rankingPenyakit('hasil_diagnosis_cbr');
        $cf = $this->rankingPenyakit('hasil_diagnosis_cf');
        $labels = $cbr->pluck('nama_penyakit')->merge($cf->pluck('nama_penyakit'))->unique()->take(8)->values();

        return [
            'labels' => $labels,
            'cbr' => $labels->map(fn ($item) => (int) optional($cbr->firstWhere('nama_penyakit', $item))->total)->values(),
            'cf' => $labels->map(fn ($item) => (int) optional($cf->firstWhere('nama_penyakit', $item))->total)->values(),
        ];
    }

    private function rankingPenyakit(string $table)
    {
        return DB::table($table)
            ->join('penyakit_hama', $table . '.penyakit_id', '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
    }
}
