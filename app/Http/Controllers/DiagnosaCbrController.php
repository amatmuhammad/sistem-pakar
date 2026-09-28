<?php

namespace App\Http\Controllers;

use App\Models\FiturKasusCbr;
use App\Models\Gejala;
use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;
use App\Models\KasusCbr;
use App\Models\KasusCf;
use App\Models\PenyakitHama;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosaCbrController extends Controller
{
    public function form()
    {
        $gejala = Gejala::all();
        // Riwayat diagnosis terakhir (opsional)
        $diagnosa = HasilDiagnosisCbr::with('penyakit')->latest()->limit(10)->get();

        return view('cbr.diagnosa', compact('gejala', 'diagnosa'));
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget('hasil');

        return redirect()
            ->route('diagnosa-cbr.form')
            ->with('success', 'Riwayat hasil diagnosa sesi berhasil dibersihkan.');
    }

    public function proses(Request $request)
    {
        // Validasi
        $request->validate([
            'gejala' => 'required|array|min:1',
            'gejala.*' => 'exists:gejala,id',
        ]);

        $kasus = KasusCbr::create([
            'user_id' => 1,
            'tanggal' => now(),
            'status_retain' => 'baru',
        ]);

        $this->simpanGejala($kasus->id, $request->gejala);

        $hasil = $this->hitungCbr($kasus);

        if (!$hasil) {
            return redirect()
                ->route('diagnosa-cbr.form')
                ->with('error', 'Tidak ada penyakit yang cocok dengan gejala yang dipilih. Silakan pilih gejala lain atau tambahkan gejala.');
        }

        $hasil->load(['kasus.fitur.gejala', 'penyakit']);

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
        $gejalaBaru = FiturKasusCbr::where('kasus_cbr_id', $kasus->id)->pluck('gejala_id')->toArray();
        if (empty($gejalaBaru)) return null;

        $penyakitList = PenyakitHama::with('basisGejala')->get(); // relasi tanpa cf_pakar
        $similarityTertinggi = 0;
        $penyakitTerbaik = null;

        foreach ($penyakitList as $penyakit) {
            $gejalaPenyakit = $penyakit->basisGejala->pluck('id')->toArray();
            if (empty($gejalaPenyakit)) continue;

            // Opsional: gunakan bobot dari tabel gejala
            $bobotCocok = Gejala::whereIn('id', $gejalaBaru)
                            ->whereIn('id', $gejalaPenyakit)->sum('bobot_cbr');
            $totalBobot = Gejala::whereIn('id', $gejalaPenyakit)->sum('bobot_cbr');
            $similarity = $totalBobot > 0 ? $bobotCocok / $totalBobot : 0;

            if ($similarity > $similarityTertinggi) {
                $similarityTertinggi = $similarity;
                $penyakitTerbaik = $penyakit;
            }
        }

        if (!$penyakitTerbaik || $similarityTertinggi == 0) return null;

        return HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $penyakitTerbaik->id,
            'similarity_final' => $similarityTertinggi,
        ]);
    }
    
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

    public function hasil(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        $hasil = HasilDiagnosisCbr::with(['kasus.fitur.gejala', 'penyakit'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('penyakit', function ($qp) use ($search) {
                    $qp->where('nama_penyakit', 'like', "%{$search}%");
                })
                ->orWhereHas('kasus', function ($qk) use ($search) {
                    $qk->where('id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('cbr.hasil', compact('hasil'));
    }


}