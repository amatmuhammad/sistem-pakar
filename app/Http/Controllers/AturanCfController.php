<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\AturanCf;
use App\Models\PenyakitHama;
use Illuminate\Http\Request;

class AturanCfController extends Controller
{
    public function index()
    {
        $data = AturanCf::with(['gejala','penyakit'])->get();

        $gejala = Gejala::all(); 
        $penyakit = PenyakitHama::all();

        return view('aturan_cf.index', compact('data','gejala','penyakit'));
    }

    public function store(Request $request)
    {
        AturanCf::create($request->all());
        return redirect()->route('aturan-cf.index');
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'penyakit_id' => 'required|exists:penyakit_hama,id',
            'gejala_id'   => 'required|exists:gejala,id',
            'mb'          => 'required|numeric|between:0,1',
            'md'          => 'required|numeric|between:0,1',
        ], [
            'mb.between' => 'Nilai MB harus di antara 0 sampai 1',
            'md.between' => 'Nilai MD harus di antara 0 sampai 1',
        ]);

        try {
            // 2. Cari data Aturan
            $aturan = AturanCf::findOrFail($id);

            // 3. (Opsional) Cek apakah kombinasi penyakit & gejala sudah ada di data lain
            // Agar tidak terjadi duplikasi aturan yang sama
            $exists = AturanCf::where('penyakit_id', $request->penyakit_id)
                ->where('gejala_id', $request->gejala_id)
                ->where('id', '!=', $id) // Abaikan data diri sendiri
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Aturan untuk penyakit dan gejala tersebut sudah ada!');
            }

            // 4. Update data
            $aturan->update([
                'penyakit_id' => $request->penyakit_id,
                'gejala_id'   => $request->gejala_id,
                'mb'          => $request->mb,
                'md'          => $request->md,
            ]);

            return redirect()->route('aturan-cf.index')->with('success', 'Aturan berhasil diperbarui!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(AturanCf $aturanCf)
    {
        $aturanCf->delete();
        return back();
    }
}
