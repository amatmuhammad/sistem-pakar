<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;
use Illuminate\Pagination\LengthAwarePaginator;

class PerbandinganController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data, urut berdasarkan created_at terbaru
        $dataCbr = HasilDiagnosisCbr::with('penyakit')
                        ->orderBy('created_at', 'desc')
                        ->get();

        $dataCf = HasilDiagnosisCf::with('penyakit')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Hanya bandingkan sejumlah data terkecil
        $total = min($dataCbr->count(), $dataCf->count());

        $benarCbr = 0;
        $benarCf = 0;
        $perbandingan = [];

        for ($i = 0; $i < $total; $i++) {
            $cbr = $dataCbr[$i];
            $cf  = $dataCf[$i];

            // Sesuaikan foreign key (penyakit_hama_id atau penyakit_id)
            $idCbr = $cbr->penyakit_hama_id;
            $idCf  = $cf->penyakit_hama_id;

            $sama = ($idCbr == $idCf);

            if ($sama) {
                $benarCbr++;
                $benarCf++;
            }

            $perbandingan[] = [
                'no'            => $i + 1,
                'tanggal_cbr'   => $cbr->created_at->format('d/m/Y H:i'),
                'tanggal_cf'    => $cf->created_at->format('d/m/Y H:i'),
                'cbr'           => $cbr->penyakit->nama_penyakit ?? 'Tidak diketahui',
                'cf'            => $cf->penyakit->nama_penyakit ?? 'Tidak diketahui',
                'status'        => $sama ? 'Sama' : 'Berbeda',
                'similarity'    => $cbr->similarity_final,       // nilai asli (0..1)
                'cf_value'      => $cf->cf_final,                // nilai asli (0..1)
                'similarity_pct'=> round($cbr->similarity_final * 100, 2), // persentase
                'cf_pct'        => round($cf->cf_final * 100, 2),
            ];
        }

        $akurasiCbr = $total > 0 ? round(($benarCbr / $total) * 100, 2) : 0;
        $akurasiCf  = $total > 0 ? round(($benarCf / $total) * 100, 2) : 0;

        // Pagination
        $perPage = (int) $request->get('perPage', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $currentPage = (int) $request->get('page', 1) ?: 1;
        $currentPath = $request->url();
        $currentQuery = $request->except('page');

        $items = collect($perbandingan)->forPage($currentPage, $perPage)->values();

        $perbandinganPaginate = new LengthAwarePaginator(
            $items,
            count($perbandingan),
            $perPage,
            $currentPage,
            [
                'path'     => $currentPath,
                'query'    => $currentQuery,
                'pageName' => 'page',
            ]
        );

        return view('perbandingan.index', compact(
            'perbandinganPaginate',
            'akurasiCbr',
            'akurasiCf',
            'total'
        ));
    }
}