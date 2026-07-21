<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\KasusCbr;
use App\Models\PenyakitHama;
use Illuminate\Http\Request;
use App\Models\FiturKasusCbr;
use App\Models\HasilDiagnosisCf;
use App\Models\HasilDiagnosisCbr;
use App\Models\KasusCf;
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

    public function proses(Request $request)
    {
        // 1. Buat kasus baru
        $kasus = KasusCbr::create([
            'user_id' => 1, // sesuaikan dengan auth()->id() jika ada login
            'tanggal' => now(),
            'status_retain' => 'baru',  // default
        ]);

        // 2. Simpan gejala yang dipilih pengguna
        $this->simpanGejala($kasus->id, $request->gejala ?? []);

        // 3. Hitung CBR dengan membandingkan ke basis pengetahuan (basis_penyakit_gejala)
        $hasil = $this->hitungCbr($kasus);

        if ($hasil) {
            $hasil->load(['kasus.fitur.gejala', 'penyakit']);
        }

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
        // Gejala yang dipilih pengguna
        $gejalaBaru = FiturKasusCbr::where('kasus_cbr_id', $kasus->id)
            ->pluck('gejala_id')
            ->toArray();

        if (empty($gejalaBaru)) {
            return null;
        }

        // Ambil semua penyakit dari basis pengetahuan
        $penyakitList = PenyakitHama::with('basisGejala')->get();

        $similarityTertinggi = 0;
        $penyakitTerbaik = null;

        foreach ($penyakitList as $penyakit) {
            $gejalaPenyakit = $penyakit->basisGejala->pluck('id')->toArray();
            if (empty($gejalaPenyakit)) {
                continue;
            }

            // Hitung gejala yang cocok
            $gejalaCocok = array_intersect($gejalaBaru, $gejalaPenyakit);
            // Rumus sesuai penjelasan: similarity = jumlah gejala cocok / total gejala pada kasus lama
            $similarity = count($gejalaCocok) / count($gejalaPenyakit);

            // Jika ingin menggunakan bobot dari tabel gejala (opsional)
            // $bobotCocok = Gejala::whereIn('id', $gejalaCocok)->sum('bobot_cbr');
            // $totalBobotPenyakit = Gejala::whereIn('id', $gejalaPenyakit)->sum('bobot_cbr');
            // $similarity = $totalBobotPenyakit > 0 ? $bobotCocok / $totalBobotPenyakit : 0;

            if ($similarity > $similarityTertinggi) {
                $similarityTertinggi = $similarity;
                $penyakitTerbaik = $penyakit;
            }
        }

        // Jika tidak ada penyakit yang cocok sama sekali
        if (!$penyakitTerbaik || $similarityTertinggi == 0) {
            return null;
        }

        // Simpan hasil diagnosis
        return HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_hama_id' => $penyakitTerbaik->id,
            'similarity_final' => $similarityTertinggi,
        ])->load('penyakit');
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

    public function statistik()
    {
        $totalKasus = KasusCbr::count();
        $totalKasusCf = KasusCf::count();
        $totalHasil = HasilDiagnosisCbr::count();
        $totalHasilCf = HasilDiagnosisCf::count();
        $totalDiagnosa = $totalHasil + $totalHasilCf;
        $rataSimilarity = HasilDiagnosisCbr::avg('similarity_final') ?? 0;
        $rataCf = HasilDiagnosisCf::avg('cf_final') ?? 0;

        // Statistik per penyakit dari CBR (kolom foreign key sudah penyakit_hama_id)
        $statistikPenyakit = DB::table('hasil_diagnosis_cbr')
            ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_hama_id', '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        // Statistik per penyakit dari CF (asumsi kolom penyakit_id, sesuaikan jika berbeda)
        $statistikPenyakitCf = DB::table('hasil_diagnosis_cf')
            ->join('penyakit_hama', 'hasil_diagnosis_cf.penyakit_id', '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        // Gabungan ranking
        $rankingGabungan = DB::query()
            ->fromSub(
                DB::table('hasil_diagnosis_cbr')
                    ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_hama_id', '=', 'penyakit_hama.id')
                    ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
                    ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
                    ->unionAll(
                        DB::table('hasil_diagnosis_cf')
                            ->join('penyakit_hama', 'hasil_diagnosis_cf.penyakit_id', '=', 'penyakit_hama.id')
                            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
                            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
                    ),
                'diagnosa'
            )
            ->select('nama_penyakit', DB::raw('SUM(total) as total'))
            ->groupBy('nama_penyakit')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Tren diagnosa
        $trenCbr = HasilDiagnosisCbr::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $trenCf = HasilDiagnosisCf::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $tanggalTren = $trenCbr->keys()->merge($trenCf->keys())->unique()->sort()->values();

        $trenDiagnosa = [
            'labels' => $tanggalTren,
            'cbr' => $tanggalTren->map(fn ($item) => (int) ($trenCbr[$item] ?? 0))->values(),
            'cf' => $tanggalTren->map(fn ($item) => (int) ($trenCf[$item] ?? 0))->values(),
        ];

        $diagnosaTertinggi = $rankingGabungan->first();
        $persentaseTertinggi = $totalDiagnosa > 0 && $diagnosaTertinggi
            ? round(($diagnosaTertinggi->total / $totalDiagnosa) * 100, 1)
            : 0;

        $rankingLabels = $rankingGabungan->pluck('nama_penyakit');
        $rankingValues = $rankingGabungan->pluck('total');

        return view('cbr.statistik', compact(
            'totalKasus',
            'totalKasusCf',
            'totalHasil',
            'totalHasilCf',
            'totalDiagnosa',
            'rataSimilarity',
            'rataCf',
            'statistikPenyakit',
            'statistikPenyakitCf',
            'rankingGabungan',
            'diagnosaTertinggi',
            'persentaseTertinggi',
            'rankingLabels',
            'rankingValues',
            'trenDiagnosa'
        ));
    }
}