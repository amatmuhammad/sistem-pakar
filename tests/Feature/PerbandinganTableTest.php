<?php

namespace Tests\Feature;

use App\Models\FiturKasusCbr;
use App\Models\Gejala;
use App\Models\GejalaKasusCf;
use App\Models\HasilDiagnosisCbr;
use App\Models\HasilDiagnosisCf;
use App\Models\KasusCbr;
use App\Models\KasusCf;
use App\Models\PenyakitHama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerbandinganTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_menampilkan_pasangan_gejala_sama_dan_tidak_menggunakan_hasil_cf_duplikat(): void
    {
        $user = User::factory()->create();
        $gejalaSatu = Gejala::create([
            'kode_gejala' => 'T01',
            'nama_gejala' => 'Gejala pertama',
            'bobot_cbr' => 1,
        ]);
        $gejalaDua = Gejala::create([
            'kode_gejala' => 'T02',
            'nama_gejala' => 'Gejala kedua',
            'bobot_cbr' => 1,
        ]);
        $penyakit = PenyakitHama::create([
            'nama_penyakit' => 'Penyakit tabel',
            'jenis' => 'hama',
        ]);

        $cbrPertama = $this->buatKasusCbr($user->id, [$gejalaSatu->id, $gejalaDua->id]);
        $hasilCbrPertama = HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $cbrPertama->id,
            'penyakit_hama_id' => $penyakit->id,
            'similarity_final' => 0.75,
        ]);

        // CBR kedua tidak boleh memakai hasil CF yang sudah dipakai CBR pertama.
        $cbrKedua = $this->buatKasusCbr($user->id, [$gejalaSatu->id, $gejalaDua->id]);
        $hasilCbrKedua = HasilDiagnosisCbr::create([
            'kasus_cbr_id' => $cbrKedua->id,
            'penyakit_hama_id' => $penyakit->id,
            'similarity_final' => 0.60,
        ]);

        $kasusCf = KasusCf::create([
            'user_id' => $user->id,
            'tanggal' => now(),
        ]);
        foreach ([$gejalaSatu->id, $gejalaDua->id] as $gejalaId) {
            GejalaKasusCf::create([
                'kasus_cf_id' => $kasusCf->id,
                'gejala_id' => $gejalaId,
                'nilai_cf_user' => 0.8,
            ]);
        }
        $hasilCf = HasilDiagnosisCf::create([
            'kasus_cf_id' => $kasusCf->id,
            'penyakit_hama_id' => $penyakit->id,
            'cf_final' => 0.80,
        ]);

        // CBR pertama adalah diagnosis terbaru; CBR kedua lebih lama.
        $hasilCbrPertama->created_at = now();
        $hasilCbrPertama->updated_at = now();
        $hasilCbrPertama->save();
        $hasilCbrKedua->created_at = now()->subMinute();
        $hasilCbrKedua->updated_at = now()->subMinute();
        $hasilCbrKedua->save();

        $response = $this->actingAs($user)
            ->get(route('perbandingan.akurasi'));

        $response->assertOk()
            ->assertSee($penyakit->nama_penyakit)
            ->assertSee('75.00%')
            ->assertSee('80.00%')
            ->assertSee('#'.$hasilCbrPertama->id)
            ->assertSee('#'.$hasilCf->id)
            ->assertDontSee('#'.$hasilCbrKedua->id);
    }

    private function buatKasusCbr(int $userId, array $gejalaIds): KasusCbr
    {
        $kasus = KasusCbr::create([
            'user_id' => $userId,
            'tanggal' => now(),
        ]);

        foreach ($gejalaIds as $gejalaId) {
            FiturKasusCbr::create([
                'kasus_cbr_id' => $kasus->id,
                'gejala_id' => $gejalaId,
                'nilai' => 1,
            ]);
        }

        return $kasus;
    }
}
