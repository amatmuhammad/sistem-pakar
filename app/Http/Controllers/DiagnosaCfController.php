<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCf;
use Illuminate\Http\Request;
use App\Models\GejalaKasusCf;
use App\Models\HasilDiagnosisCf;

class DiagnosaCfController extends Controller
{
    //
    public function form()
    {
        $gejala = Gejala::all();
        return view('cf.diagnosa', compact('gejala'));
    }

    public function proses(Request $request)
    {
        $kasus = KasusCf::create([
            // 'user_id' => auth()->id(),
            'tanggal' => now()
        ]);

        foreach ($request->cf as $gejalaId => $nilai) {
            GejalaKasusCf::create([
                'kasus_cf_id' => $kasus->id,
                'gejala_id' => $gejalaId,
                'nilai_cf_user' => $nilai
            ]);
        }

        // dummy hasil CF
        HasilDiagnosisCf::create([
            'kasus_cf_id' => $kasus->id,
            'penyakit_id' => 1,
            'cf_final' => 0.75
        ]);

        return redirect()->route('cf.hasil', $kasus->id);
    }

    public function hasil($id)
    {
        $kasus = KasusCf::with('hasil.penyakit')->findOrFail($id);
        return view('cf.hasil', compact('kasus'));
    }
}
