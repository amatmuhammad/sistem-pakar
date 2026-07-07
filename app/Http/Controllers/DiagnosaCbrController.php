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
        // Ambil diagnosa terakhir untuk ditampilkan sebagai riwayat singkat di form jika perlu
        $diagnosa = HasilDiagnosisCbr::with('penyakit')->latest()->limit(10)->get();

        return view('cbr.diagnosa', compact('gejala', 'diagnosa'));
    }

    public function proses(Request $request)
    {
        // 1. Buat Kasus Baru
        $kasus = KasusCbr::create([
            'user_id' => 1, // Sesuaikan jika ada auth: auth()->id()
            'tanggal' => now()
        ]);

        // 2. Simpan Gejala yang dipilih
        $this->simpanGejala($kasus->id, $request->gejala ?? []);

        // 3. Hitung CBR
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
        // Ambil gejala kasus baru
        $gejalaBaru = FiturKasusCbr::where('kasus_cbr_id', $kasus->id)
            ->pluck('gejala_id')
            ->toArray();

        if (empty($gejalaBaru)) return null;

        // Ambil bobot gejala kasus baru
        $bobotGejala = Gejala::whereIn('id', $gejalaBaru)
            ->pluck('bobot_cbr', 'id')
            ->toArray();

        $totalBobot = array_sum($bobotGejala);

        // Ambil kasus lama (basis pengetahuan) beserta fiturnya
        $kasusLama = KasusCbr::where('id', '!=', $kasus->id)->with('fitur')->get();

        $similarityMax = 0;
        $kasusTerbaik = null;

        foreach ($kasusLama as $lama) {
            $gejalaLama = $lama->fitur->pluck('gejala_id')->toArray();
            $nilai = 0;

            foreach ($gejalaBaru as $idGejala) {
                if (in_array($idGejala, $gejalaLama)) {
                    $nilai += $bobotGejala[$idGejala] ?? 0;
                }
            }

            $similarity = $totalBobot > 0 ? $nilai / $totalBobot : 0;

            if ($similarity > $similarityMax) {
                $similarityMax = $similarity;
                $kasusTerbaik = $lama;
            }
        }

        // Jika tidak ada kemiripan sama sekali
        if (!$kasusTerbaik || $similarityMax == 0) {
             $kasus->update(['nilai_similarity' => 0]);
             return null;
        }

        // Ambil hasil penyakit dari kasus yang paling mirip
        // Cari di tabel hasil_diagnosis_cbr berdasarkan id kasus terbaik
        $hasilLama = HasilDiagnosisCbr::where('kasus_cbr_id', $kasusTerbaik->id)->first();
        
        if (!$hasilLama) return null;

        // Update tabel kasus_cbr (kolom penyakit_id yang baru ditambahkan)
        $kasus->update([
            'nilai_similarity' => $similarityMax,
            'penyakit_id' => $hasilLama->penyakit_id
        ]);

        // Simpan ke tabel hasil_diagnosis_cbr
        return HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $kasus->id,
            'penyakit_id' => $hasilLama->penyakit_id,
            'similarity_final' => $similarityMax
        ])->load('penyakit');
    }

    public function kasus(Request $request) // Tambahkan parameter Request di sini
    {
        // Mengambil input dari user untuk pagination dan pencarian
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        $kasus = KasusCbr::with(['fitur.gejala', 'hasil.penyakit'])
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    // 1. Cari berdasarkan ID Kasus
                    $q->where('id', 'like', "%{$search}%")
                    // 2. Cari berdasarkan Nama Penyakit (Nested Relationship)
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

        $statistikPenyakit = DB::table('hasil_diagnosis_cbr')
            ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_id', '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        $statistikPenyakitCf = DB::table('hasil_diagnosis_cf')
            ->join('penyakit_hama', 'hasil_diagnosis_cf.penyakit_id', '=', 'penyakit_hama.id')
            ->select('penyakit_hama.nama_penyakit', DB::raw('COUNT(*) as total'))
            ->groupBy('penyakit_hama.id', 'penyakit_hama.nama_penyakit')
            ->orderByDesc('total')
            ->get();

        $rankingGabungan = DB::query()
            ->fromSub(
                DB::table('hasil_diagnosis_cbr')
                    ->join('penyakit_hama', 'hasil_diagnosis_cbr.penyakit_id', '=', 'penyakit_hama.id')
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
