<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilDiagnosisCf;
use App\Models\HasilDiagnosisCbr;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PerbandinganController extends Controller
{
    //
    public function index(Request $request)
    {
        $dataCbr = HasilDiagnosisCbr::with('penyakit')->get();
        $dataCf  = HasilDiagnosisCf::with('penyakit')->get();

        $total = min($dataCbr->count(), $dataCf->count());

        $benarCbr = 0;
        $benarCf = 0;

        $perbandingan = [];

        for ($i = 0; $i < $total; $i++) {

            $cbr = $dataCbr[$i];
            $cf  = $dataCf[$i];

            $sama = $cbr->penyakit_id == $cf->penyakit_id;

            if ($sama) {
                $benarCbr++;
                $benarCf++;
            }

            $perbandingan[] = [
                'no' => $i + 1,
                'cbr' => $cbr->penyakit->nama_penyakit,
                'cf' => $cf->penyakit->nama_penyakit,
                'status' => $sama ? 'Sama' : 'Berbeda'
            ];
        }

        $akurasiCbr = $total ? ($benarCbr / $total) * 100 : 0;
        $akurasiCf  = $total ? ($benarCf / $total) * 100 : 0;

        // Paginasi manual karena data berupa array
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
                'path' => $currentPath,
                'query' => $currentQuery,
                'pageName' => 'page',
            ]
        );

        Paginator::useBootstrapFive();

        return view('perbandingan.index', compact(
            'perbandinganPaginate',
            'akurasiCbr',
            'akurasiCf',
            'total'
        ));
    }
}
