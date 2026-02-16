<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Illuminate\Http\Request;
use App\Models\FiturKasusCbr;
use App\Models\HasilDiagnosisCbr;

class DiagnosaCbrController extends Controller
{
    //
    public function form()
    {
        $gejala = Gejala::all();

        $diagnosa = HasilDiagnosisCbr::all();

        return view('cbr.diagnosa', compact('gejala', 'diagnosa'));
    }

   public function proses(Request $request)
    {
        $kasus = KasusCbr::create([
            'user_id' => 1,
            'tanggal' => now()
        ]);

        $this->simpanGejala($kasus->id, $request->gejala ?? []);

        $hasil = $this->hitungCbr($kasus);

        return redirect()
            ->route('diagnosa-cbr.form')
            ->with('hasil', $hasil);
    }

    private function simpanGejala($kasusId, array $gejala)
    {
        foreach ($gejala as $id) {
            FiturKasusCbr::create([
                'kasus_cbr_id' => $kasusId,
                'gejala_id' => $id,
                'nilai' => 1
            ]);
        }
    }

    private function hitungCbr($kasus)
    {
        $gejalaBaru = FiturKasusCbr::where('kasus_cbr_id', $kasus->id)
            ->pluck('gejala_id')
            ->toArray();

        if (empty($gejalaBaru)) return null;

        $bobotGejala = Gejala::whereIn('id', $gejalaBaru)
            ->pluck('bobot_cbr', 'id')
            ->toArray();

        $totalBobot = array_sum($bobotGejala);

        $kasusLama = KasusCbr::where('id', '!=', $kasus->id)->get();

        $similarityMax = 0;
        $kasusTerbaik = null;

        foreach ($kasusLama as $lama) {

            $gejalaLama = FiturKasusCbr::where('kasus_cbr_id', $lama->id)
                ->pluck('gejala_id')
                ->toArray();

            $nilai = 0;

            foreach ($gejalaBaru as $idGejala) {
                if (in_array($idGejala, $gejalaLama)) {
                    $nilai += $bobotGejala[$idGejala];
                }
            }

            $similarity = $totalBobot > 0 ? $nilai / $totalBobot : 0;

            if ($similarity > $similarityMax) {
                $similarityMax = $similarity;
                $kasusTerbaik = $lama;
            }
        }

        if (!$kasusTerbaik) {
            // $penyakit = PenyakitHama::first();
            return null;
            return HasilDiagnosisCbr::create([
                'kasus_cbr_id' => $kasus->id,
                'penyakit_id' => $penyakit->id,
                'similarity_final' => 0
            ])->load('penyakit');
        }

        $hasilLama = HasilDiagnosisCbr::where('kasus_cbr_id', $kasusTerbaik->id)->first();

        $kasus->update([
            'nilai_similarity' => $similarityMax
        ]);

        return HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_id' => $hasilLama->penyakit_id,
            'similarity_final' => $similarityMax
        ])->load('penyakit');
    }



    public function hasil($id)
    {
        $kasus = KasusCbr::with('hasil.penyakit')->findOrFail($id);
        return redirect()->route('diagnosa-cbr.form')->with('success', 'Berhasil memproses data Hasil CBR anda');
    }
}
