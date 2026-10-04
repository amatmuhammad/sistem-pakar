<?php

namespace App\Http\Controllers;

use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PerbandinganController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        // Urut terbaru lebih dulu agar pasangan dengan gejala sama dibuat 1:1.
        $dataCbr = HasilDiagnosisCbr::with(['kasus.fitur.gejala', 'penyakit'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $dataCf = HasilDiagnosisCf::with(['kasus.gejala.gejala', 'penyakit'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        // Kelompokkan CF berdasarkan user + set gejala yang sama.
        $cfByDiagnosis = [];

        foreach ($dataCf as $cf) {
            $key = $this->diagnosisKey($cf);

            if ($key === '') {
                continue;
            }

            $cfByDiagnosis[$key][] = $cf;
        }

        $perbandingan = [];
        $cbrTerpakai = [];
        $cfTerpakai = [];

        foreach ($dataCbr as $cbr) {
            $key = $this->diagnosisKey($cbr);

            if ($key === '' || empty($cfByDiagnosis[$key])) {
                continue;
            }

            // array_shift memastikan satu hasil CF tidak dipakai lebih dari satu kali.
            $cf = array_shift($cfByDiagnosis[$key]);
            $cbrTanggal = $this->formatTanggal($cbr->kasus?->tanggal ?: $cbr->created_at);
            $cfTanggal = $this->formatTanggal($cf->kasus?->tanggal ?: $cf->created_at);
            $sama = (int) $cbr->penyakit_hama_id === (int) $cf->penyakit_hama_id;

            $cbrTerpakai[] = $cbr->id;
            $cfTerpakai[] = $cf->id;

            $perbandingan[] = [
                'no' => count($perbandingan) + 1,
                'id_cbr' => $cbr->id,
                'id_cf' => $cf->id,
                'tanggal_cbr' => $cbrTanggal,
                'tanggal_cf' => $cfTanggal,
                'cbr' => $cbr->penyakit?->nama_penyakit ?? 'Tidak diketahui',
                'cf' => $cf->penyakit?->nama_penyakit ?? 'Tidak diketahui',
                'status' => $sama ? 'Sama' : 'Berbeda',
                'similarity_pct' => round((float) $cbr->similarity_final * 100, 2),
                'cf_pct' => round((float) $cf->cf_final * 100, 2),
            ];
        }

        // Tampilkan semua diagnosis CBR yang tidak punya pasangan CF.
        foreach ($dataCbr as $cbr) {
            if (in_array($cbr->id, $cbrTerpakai, true)) {
                continue;
            }

            $perbandingan[] = [
                'no' => count($perbandingan) + 1,
                'id_cbr' => $cbr->id,
                'id_cf' => null,
                'tanggal_cbr' => $this->formatTanggal($cbr->kasus?->tanggal ?: $cbr->created_at),
                'tanggal_cf' => null,
                'cbr' => $cbr->penyakit?->nama_penyakit ?? 'Tidak diketahui',
                'cf' => '—',
                'status' => 'Tidak ada pasangan',
                'similarity_pct' => round((float) $cbr->similarity_final * 100, 2),
                'cf_pct' => null,
            ];
        }

        // Tampilkan semua diagnosis CF yang tidak punya pasangan CBR.
        foreach ($dataCf as $cf) {
            if (in_array($cf->id, $cfTerpakai, true)) {
                continue;
            }

            $perbandingan[] = [
                'no' => count($perbandingan) + 1,
                'id_cbr' => null,
                'id_cf' => $cf->id,
                'tanggal_cbr' => null,
                'tanggal_cf' => $this->formatTanggal($cf->kasus?->tanggal ?: $cf->created_at),
                'cbr' => '—',
                'cf' => $cf->penyakit?->nama_penyakit ?? 'Tidak diketahui',
                'status' => 'Tidak ada pasangan',
                'similarity_pct' => null,
                'cf_pct' => round((float) $cf->cf_final * 100, 2),
            ];
        }

        $currentPage = max(1, (int) $request->get('page', 1));
        $items = collect($perbandingan)->forPage($currentPage, $perPage)->values();

        $perbandinganPaginate = new LengthAwarePaginator(
            $items,
            count($perbandingan),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->except('page'),
            ]
        );

        return view('perbandingan.index', [
            'perbandinganPaginate' => $perbandinganPaginate,
        ]);
    }

    private function formatTanggal($tanggal): ?string
    {
        return $tanggal ? Carbon::parse($tanggal)->format('d/m/Y H:i') : null;
    }

    /**
     * Hanya diagnosis dengan user dan set gejala yang sama yang dapat
     * dipasangkan. Hasil tanpa key tidak dipaksakan.
     */
    private function diagnosisKey($hasil): string
    {
        $gejala = $hasil instanceof HasilDiagnosisCbr
            ? ($hasil->kasus?->fitur ?? collect())
            : ($hasil->kasus?->gejala ?? collect());

        $gejalaIds = collect($gejala)
            ->pluck('gejala_id')
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values();

        if ($gejalaIds->isEmpty()) {
            return '';
        }

        $userId = $hasil->kasus?->user_id ?? 'tanpa-user';

        return $userId.'|'.$gejalaIds->implode(',');
    }
}
