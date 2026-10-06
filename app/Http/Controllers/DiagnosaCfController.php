<?php

namespace App\Http\Controllers;

use App\Models\HasilDiagnosisCf;
use Illuminate\Http\Request;

class DiagnosaCfController extends Controller
{
    public function hasil(Request $request)
    {
        $perPage = (int) $request->get('perPage', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $hasil = HasilDiagnosisCf::with(['penyakit', 'kasus.gejala.gejala'])
                    ->when($request->search, function ($query, $search) {
                        $query->whereHas('penyakit', function ($penyakitQuery) use ($search) {
                            $penyakitQuery->where('nama_penyakit', 'like', '%' . $search . '%');
                        });
                    })
                    ->latest()
                    ->paginate($perPage)
                    ->withQueryString();

        return view('cf.hasil', compact('hasil'));
    }
}