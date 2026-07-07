<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCf;
use App\Models\AturanCf;
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

    public function proses(Request $request)
    {
        // 1. Filter input CF yang valid (> 0)
        $cfInput = array_filter(
            $request->cf ?? [],
            fn ($v) => $v !== null && $v !== '' && (float) $v > 0
        );

        if (empty($cfInput)) {
            return redirect()
                ->route('diagnosa-cf.form')
                ->with('error', 'Pilih minimal satu gejala beserta tingkat keyakinannya.');
        }

        try {
            return DB::transaction(function () use ($cfInput) {
                // 2. Simpan Header Kasus
                $kasus = KasusCf::create([
                    'user_id' => 1,
                    'tanggal' => now()
                ]);

                // 3. Simpan Gejala & Nilai CF dari User
                foreach ($cfInput as $gejalaId => $nilaiUser) {
                    GejalaKasusCf::create([
                        'kasus_cf_id' => $kasus->id,
                        'gejala_id' => $gejalaId,
                        'nilai_cf_user' => (float) $nilaiUser
                    ]);
                }

                // 4. Hitung CF
                $hasil = $this->hitungCf($kasus->id);

                // 5. Jika tidak ada rule yang cocok -> abort transaksi
                if (! $hasil) {
                    throw new \RuntimeException('NO_MATCH');
                }

                // 6. Load relasi untuk ditampilkan di form
                $hasil->load(['penyakit', 'kasus.gejala.gejala']);

                return redirect()
                    ->route('diagnosa-cf.form')
                    ->with('hasil', $hasil);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'NO_MATCH') {
                // Transaksi otomatis di-rollback (kasus & gejala ikut terhapus)
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

        $cfPenyakit = [];

        foreach ($gejalaUser as $g) {
            // Ambil semua rule yang relevan untuk gejala ini
            $rules = AturanCf::where('gejala_id', $g->gejala_id)->get();

            foreach ($rules as $rule) {
                // CF(H,E) = (MB - MD) * CFuser
                $cfRule = ($rule->mb - $rule->md) * (float) $g->nilai_cf_user;
                $pid = $rule->penyakit_id;

                if (! isset($cfPenyakit[$pid])) {
                    $cfPenyakit[$pid] = $cfRule;
                } else {
                    $cfPenyakit[$pid] = $this->combineCf($cfPenyakit[$pid], $cfRule);
                }
            }
        }

        if (empty($cfPenyakit)) return null;

        // Urutkan dari nilai keyakinan tertinggi
        arsort($cfPenyakit);

        // Pastikan nilai tetap di rentang -1 .. 1
        $cfPenyakit = array_map(fn ($v) => max(-1, min(1, $v)), $cfPenyakit);

        $penyakitId = array_key_first($cfPenyakit);
        $cfFinal = $cfPenyakit[$penyakitId] ?? 0;

        // Abaikan jika nilai keyakinan akhir <= 0 (tidak meyakinkan)
        if ($cfFinal <= 0) return null;

        return HasilDiagnosisCf::create([
            'kasus_cf_id' => $kasusId,
            'penyakit_id' => $penyakitId,
            'cf_final' => $cfFinal
        ])->load('penyakit');
    }

    /**
     * Kombinasi dua nilai CF sesuai aturan Certainty Factor:
     *  - Kedua positif : CF1 + CF2 * (1 - CF1)
     *  - Kedua negatif : CF1 + CF2 * (1 + CF1)
     *  - Tanda beda    : (CF1 + CF2) / (1 - min(|CF1|, |CF2|))
     */
    private function combineCf($cf1, $cf2)
    {
        if ($cf1 >= 0 && $cf2 >= 0) {
            return $cf1 + $cf2 * (1 - $cf1);
        }

        if ($cf1 < 0 && $cf2 < 0) {
            return $cf1 + $cf2 * (1 + $cf1);
        }

        $denom = 1 - min(abs($cf1), abs($cf2));

        return $denom != 0 ? ($cf1 + $cf2) / $denom : 0;
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