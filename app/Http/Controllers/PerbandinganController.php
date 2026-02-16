<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilDiagnosisCf;
use App\Models\HasilDiagnosisCbr;

class PerbandinganController extends Controller
{
    //
    public function akurasi()
    {
        $cbr = HasilDiagnosisCbr::count();
        $cf  = HasilDiagnosisCf::count();

        return view('perbandingan.akurasi', compact('cbr','cf'));
    }
}
