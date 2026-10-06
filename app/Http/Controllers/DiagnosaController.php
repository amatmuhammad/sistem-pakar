<?php

namespace App\Http\Controllers;

use App\Models\FiturKasusCbr;
use App\Models\Gejala;
use App\Models\GejalaKasusCf;
use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;
use App\Models\KasusCbr;
use App\Models\KasusCf;
use App\Models\PenyakitHama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    /* ================= FORM GABUNGAN ================= */
    public function form()
    {
        $gejala = Gejala::orderBy('kode_gejala')->get();

        return view('diagnosa.index', compact('gejala'));
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget(['hasil_cbr', 'hasil_cf']);

        return redirect()
            ->route('diagnosa.form')
            ->with('success', 'Riwayat hasil diagnosa sesi berhasil dibersihkan.');
    }

    /* ================= PROSES GABUNGAN (CBR + CF) ================= */
    public function proses(Request $request)
    {
        $request->validate([
            'gejala'   => 'required|array|min:1',
            'gejala.*' => 'exists:gejala,id',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // ---------- PROSES CBR ----------
                $kasusCbr = KasusCbr::create([
                    'user_id'       => auth()->id() ?? 1,
                    'tanggal'       => now(),
                    'status_retain' => 'baru',
                ]);

                foreach ($request->gejala as $gejalaId) {
                    FiturKasusCbr::create([
                        'kasus_cbr_id' => $kasusCbr->id,
                        'gejala_id'    => $gejalaId,
                        'nilai'        => 1,
                    ]);
                }

                $hasilCbr = $this->hitungCbr($kasusCbr);
                if ($hasilCbr) {
                    $hasilCbr->load(['kasus.fitur.gejala', 'penyakit']);
                }

                // ---------- PROSES CF ----------
                $kasusCf = KasusCf::create([
                    'user_id' => auth()->id() ?? 1,
                    'tanggal' => now(),
                ]);

                foreach ($request->gejala as $gejalaId) {
                    GejalaKasusCf::create([
                        'kasus_cf_id'   => $kasusCf->id,
                        'gejala_id'     => $gejalaId,
                        // Nilai keyakinan user di-default-kan 1.0 (Sangat Yakin)
                        'nilai_cf_user' => 1.0,
                    ]);
                }

                $hasilCf = $this->hitungCf($kasusCf->id);
                if ($hasilCf) {
                    $hasilCf->load(['penyakit', 'kasus.gejala.gejala']);
                }

                if (!$hasilCbr && !$hasilCf) {
                    throw new \RuntimeException('NO_MATCH');
                }

                return redirect()
                    ->route('diagnosa.form')
                    ->with('hasil_cbr', $hasilCbr)
                    ->with('hasil_cf', $hasilCf);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'NO_MATCH') {
                return redirect()
                    ->route('diagnosa.form')
                    ->with('error', 'Tidak ada penyakit yang cocok dengan gejala yang dipilih. Silakan pilih gejala lain atau tambahkan gejala.');
            }
            throw $e;
        }
    }

    /* ================= RIWAYAT GABUNGAN (BASIS KASUS CBR + HASIL CF) ================= */
    public function riwayat(Request $request)
    {
        $metode = $request->get('metode'); // CBR | CF | null
        $search = $request->get('search');
        $perPage = (int) $request->get('perPage', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $rows = collect();

        // Data CBR
        if (!$metode || $metode === 'CBR') {
            $kasusCbr = KasusCbr::with(['fitur.gejala', 'hasil.penyakit'])->get();
            foreach ($kasusCbr as $k) {
                $rows->push([
                    'metode'        => 'CBR',
                    'id'            => $k->id,
                    'tanggal'       => $k->tanggal,
                    'jumlah_gejala' => $k->fitur->count(),
                    'gejala'        => $k->fitur->map(fn ($f) => $f->gejala->nama_gejala ?? '-')->all(),
                    'penyakit'      => optional($k->hasil)->penyakit->nama_penyakit ?? null,
                    'nilai'         => optional($k->hasil)->similarity_final,
                    'status'        => $k->status_retain,
                ]);
            }
        }

        // Data CF
        if (!$metode || $metode === 'CF') {
            $hasilCf = HasilDiagnosisCf::with(['penyakit', 'kasus.gejala.gejala'])->get();
            foreach ($hasilCf as $h) {
                $rows->push([
                    'metode'        => 'CF',
                    'id'            => $h->kasus_cf_id,
                    'tanggal'       => optional($h->kasus)->tanggal,
                    'jumlah_gejala' => optional($h->kasus)->gejala ? $h->kasus->gejala->count() : 0,
                    'gejala'        => optional($h->kasus)->gejala
                        ? $h->kasus->gejala->map(fn ($g) => $g->gejala->nama_gejala ?? '-')->all()
                        : [],
                    'penyakit'      => optional($h->penyakit)->nama_penyakit,
                    'nilai'         => $h->cf_final,
                    'status'        => '-',
                ]);
            }
        }

        // Filter pencarian (penyakit / id)
        if ($search) {
            $rows = $rows->filter(function ($r) use ($search) {
                return str_contains(strtolower((string) $r['penyakit']), strtolower($search))
                    || str_contains((string) $r['id'], $search);
            });
        }

        // Urutkan tanggal terbaru
        $rows = $rows->sortByDesc('tanggal')->values();

        // Pagination manual
        $page = $request->get('page', 1);
        $riwayat = new \Illuminate\Pagination\LengthAwarePaginator(
            $rows->forPage($page, $perPage),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('riwayat.index', compact('riwayat', 'metode'));
    }

    /* ================= HITUNG CBR (diambil dari DiagnosaCbrController) ================= */
    private function hitungCbr($kasus)
    {
        $gejalaBaru = FiturKasusCbr::where('kasus_cbr_id', $kasus->id)->pluck('gejala_id')->toArray();
        if (empty($gejalaBaru)) return null;

        $penyakitList = PenyakitHama::with('basisGejala')->get();
        $similarityTertinggi = 0;
        $penyakitTerbaik = null;

        foreach ($penyakitList as $penyakit) {
            $gejalaPenyakit = $penyakit->basisGejala->pluck('id')->toArray();
            if (empty($gejalaPenyakit)) continue;

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
            'kasus_cbr_id'     => $kasus->id,
            'penyakit_hama_id' => $penyakitTerbaik->id,
            'similarity_final' => $similarityTertinggi,
        ]);
    }

    /* ================= HITUNG CF (diambil dari DiagnosaCfController) ================= */
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

        arsort($cfPenyakit);

        $penyakitId = array_key_first($cfPenyakit);
        $cfFinal = $cfPenyakit[$penyakitId];

        $threshold = 0.5;
        if ($cfFinal < $threshold) {
            return null;
        }

        return HasilDiagnosisCf::create([
            'kasus_cf_id'      => $kasusId,
            'penyakit_hama_id' => $penyakitId,
            'cf_final'         => $cfFinal,
        ]);
    }
}
