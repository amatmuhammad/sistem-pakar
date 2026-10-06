<?php

namespace App\Http\Controllers;

use App\Models\KasusCbr;
use Illuminate\Http\Request;

class DiagnosaCbrController extends Controller
{
    public function kasus(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        $kasus = KasusCbr::with(['fitur.gejala', 'hasil.penyakit'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhereHas('hasil.penyakit', function ($qp) use ($search) {
                          $qp->where('nama_penyakit', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('cbr.kasus', compact('kasus'));
    }
}