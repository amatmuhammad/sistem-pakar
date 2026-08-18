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


    public function statistik()
    {
        $totalKasus = KasusCbr::count();
        $totalKasusCf = KasusCf::count();
        $totalHasil = HasilDiagnosisCbr::count();
        $totalHasilCf = HasilDiagnosisCf::count();
        $totalDiagnosa = $totalHasil + $totalHasilCf;
        $rataSimilarity = HasilDiagnosisCbr::avg('similarity_final') ?? 0;
        $rataCf = HasilDiagnosisCf::avg('cf_final') ?? 0;

        // 1. Tentukan rentang waktu 7 hari terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Statistik per penyakit dari CBR (Filter 7 hari terakhir)
        $statistikPenyakit = DB::table('hasil_diagnosis_cbr')
            ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_hama_id', '=', 'penyakit_hama.id')
            ->whereBetween('hasil_diagnosis_cbr.created_at', [$startDate, $endDate])
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        // Statistik per penyakit dari CF (Filter 7 hari terakhir)
        $statistikPenyakitCf = DB::table('hasil_diagnosis_cf')
            ->join('penyakit_hama', 'hasil_diagnosis_cf.penyakit_hama_id', '=', 'penyakit_hama.id')
            ->whereBetween('hasil_diagnosis_cf.created_at', [$startDate, $endDate])
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        // Gabungan ranking (Filter 7 hari terakhir)
        $rankingGabungan = DB::query()
            ->fromSub(
                DB::table('hasil_diagnosis_cbr')
                    ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_hama_id', '=', 'penyakit_hama.id')
                    ->whereBetween('hasil_diagnosis_cbr.created_at', [$startDate, $endDate])
                    ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
                    ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
                    ->unionAll(
                        DB::table('hasil_diagnosis_cf')
                            ->join('penyakit_hama', 'hasil_diagnosis_cf.penyakit_hama_id', '=', 'penyakit_hama.id')
                            ->whereBetween('hasil_diagnosis_cf.created_at', [$startDate, $endDate])
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

        // 2. Tren diagnosa di-generate pasti 7 hari terakhir (beserta tanggal kosong yang diisi 0)
        $trenCbr = HasilDiagnosisCbr::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $trenCf = HasilDiagnosisCf::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Looping 7 hari penuh agar label selalu lengkap 7 hari ke belakang
        $tanggalTren = [];
        $cbrData = [];
        $cfData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M'); // Format tampilan label (misal: 16 Jul)

            $tanggalTren[] = $displayDate;
            $cbrData[] = (int) ($trenCbr[$formattedDate] ?? 0);
            $cfData[] = (int) ($trenCf[$formattedDate] ?? 0);
        }

        $trenDiagnosa = [
            'labels' => $tanggalTren,
            'cbr' => $cbrData,
            'cf' => $cfData,
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