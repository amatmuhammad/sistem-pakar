<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCf;
use App\Models\PenyakitHama;
use Illuminate\Http\Request;
use App\Models\GejalaKasusCf;
use App\Models\HasilDiagnosisCf;
use Illuminate\Support\Facades\DB;

class DiagnosaCfController extends Controller
{
    public function form()
    {
        return view('cf.diagnosa', [
            'gejala' => Gejala::orderBy('kode_gejala')->get()
        ]);
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget('hasil');

        return redirect()
            ->route('diagnosa-cf.form')
            ->with('success', 'Riwayat hasil diagnosa sesi berhasil dibersihkan.');
    }

    public function proses(Request $request)
    {
        $cfInput = $request->cf ?? [];
        $cfInput = array_filter($cfInput, function ($nilaiUser) {
            return $nilaiUser !== null && $nilaiUser !== '' && (float) $nilaiUser > 0;
        });

        if (empty($cfInput)) {
            return redirect()
                ->route('diagnosa-cf.form')
                ->with('error', 'Pilih minimal satu gejala beserta tingkat keyakinannya.');
        }

        try {
            return DB::transaction(function () use ($cfInput) {
                // Simpan kasus
                $kasus = KasusCf::create([
                    'user_id' => 1,
                    'tanggal'  => now(),
                ]);

                // Simpan detail gejala (tabel gejala_kasus_cf)
                foreach ($cfInput as $gejalaId => $nilaiUser) {
                    GejalaKasusCf::create([
                        'kasus_cf_id'   => $kasus->id,
                        'gejala_id'     => $gejalaId,
                        'nilai_cf_user' => (float) $nilaiUser,
                    ]);
                }

                // Hitung CF
                $hasil = $this->hitungCf($kasus->id);

                if (!$hasil) {
                    throw new \RuntimeException('NO_MATCH');
                }

                $hasil->load(['penyakit', 'kasus.gejala.gejala']);

                return redirect()
                    ->route('diagnosa-cf.form')
                    ->with('hasil', $hasil);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'NO_MATCH') {
                return redirect()
                    ->route('diagnosa-cf.form')
                    ->with('error', 'Gejala yang dipilih tidak cukup untuk menentukan penyakit. Silakan pilih gejala lain.');
            }
            throw $e;
        }
    }

    private function hitungCf($kasusId)
    {
        $gejalaUser = GejalaKasusCf::where('kasus_cf_id', $kasusId)->get();
        if ($gejalaUser->isEmpty()) return null;

        $penyakitList = PenyakitHama::with('basisGejalaPivot')->get();
        $cfPenyakit = [];

        foreach ($penyakitList as $penyakit) {
            $cfCombine = 0;

            foreach ($gejalaUser as $g) {
                $basis = $penyakit->basisGejalaPivot->firstWhere('id', $g->gejala_id);
                if ($basis && isset($basis->pivot->cf_pakar)) {
                    $cfGejala = $basis->pivot->cf_pakar * $g->nilai_cf_user;
                    $cfCombine = ($cfCombine == 0) 
                        ? $cfGejala 
                        : $cfCombine + $cfGejala * (1 - $cfCombine);
                }
            }

            if ($cfCombine > 0) {
                $cfPenyakit[$penyakit->id] = $cfCombine;
            }
        }

        if (empty($cfPenyakit)) return null;

        // Urutkan CF tertinggi
        arsort($cfPenyakit);
        
        // Ambil penyakit dengan CF tertinggi
        $penyakitId = array_key_first($cfPenyakit);
        $cfFinal = $cfPenyakit[$penyakitId];

        // Validasi final sebelum simpan
        $threshold = 0.5; // Ambang batas CF minimal untuk dianggap valid
        if ($cfFinal < $threshold) {
            return null;
        }

        // Cek apakah penyakit_id valid
        // $penyakitExists = PenyakitHama::find($penyakitId);
        // if (!$penyakitExists) {
        //     return null;
        // }

        // Simpan hasil
        return HasilDiagnosisCf::create([
            'kasus_cf_id'      => $kasusId,
            'penyakit_hama_id' => $penyakitId,
            'cf_final'         => $cfFinal,
        ]);
    }

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