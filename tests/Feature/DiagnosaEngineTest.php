<?php

namespace Tests\Feature;

use App\Models\Gejala;
use App\Models\PenyakitHama;
use App\Services\DiagnosaEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiagnosaEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_cbr_memilih_penyakit_dengan_similarity_tertinggi(): void
    {
        $gejalaA = $this->buatGejala('G01', 1.8);
        $gejalaB = $this->buatGejala('G02', 1.5);
        $gejalaC = $this->buatGejala('G03', 1.6);

        $penyakit = $this->buatPenyakit('Bulai');
        $this->pasangBasis($penyakit, [$gejalaA, $gejalaB, $gejalaC]);

        $hasil = app(DiagnosaEngine::class)->cbr([$gejalaA->id, $gejalaB->id, $gejalaC->id]);

        $this->assertNotNull($hasil);
        $this->assertSame($penyakit->id, $hasil['penyakit_hama_id']);
        // Semua gejala basis tercocokkan -> similarity 1.
        $this->assertEqualsWithDelta(1.0, $hasil['similarity_final'], 0.0001);
    }

    public function test_cbr_menghitung_similarity_hanya_dari_gejala_yang_cocok(): void
    {
        $gejalaA = $this->buatGejala('G01', 1.8);
        $gejalaB = $this->buatGejala('G02', 1.5);
        $gejalaC = $this->buatGejala('G03', 1.6);

        $penyakit = $this->buatPenyakit('Bulai');
        $this->pasangBasis($penyakit, [$gejalaA, $gejalaB, $gejalaC]);

        $hasil = app(DiagnosaEngine::class)->cbr([$gejalaA->id, $gejalaB->id]);

        $this->assertNotNull($hasil);
        // (1.8 + 1.5) / (1.8 + 1.5 + 1.6)
        $this->assertEqualsWithDelta(3.3 / 4.9, $hasil['similarity_final'], 0.0001);
    }

    public function test_cbr_memakai_bobot_gejala_dan_bukan_jumlah_gejala(): void
    {
        $gejalaBerat = $this->buatGejala('G01', 3.0);
        $gejalaRingan = $this->buatGejala('G02', 1.0);

        $penyakit = $this->buatPenyakit('Bulai');
        $this->pasangBasis($penyakit, [$gejalaBerat, $gejalaRingan]);

        // Hanya gejala ringan yang dipilih: bobotnya kecil dibanding total bobot.
        $hasil = app(DiagnosaEngine::class)->cbr([$gejalaRingan->id]);

        $this->assertNotNull($hasil);
        $this->assertEqualsWithDelta(0.25, $hasil['similarity_final'], 0.0001);
    }

    public function test_cf_menggabungkan_keyakinan_dan_melewati_ambang(): void
    {
        $gejala = $this->buatGejala('G01', 1.8);

        $penyakit = $this->buatPenyakit('Bulai');
        $this->pasangBasis($penyakit, [$gejala], 0.9);

        $hasil = app(DiagnosaEngine::class)->cf([$gejala->id]);

        $this->assertNotNull($hasil);
        $this->assertSame($penyakit->id, $hasil['penyakit_hama_id']);
        $this->assertEqualsWithDelta(0.9, $hasil['cf_final'], 0.0001);
    }

    public function test_cf_mengembalikan_null_bila_keyakinan_dibawah_ambang(): void
    {
        $gejala = $this->buatGejala('G01', 1.8);

        $penyakit = $this->buatPenyakit('Bulai');
        $this->pasangBasis($penyakit, [$gejala], 0.4);

        $this->assertNull(app(DiagnosaEngine::class)->cf([$gejala->id]));
    }

    public function test_engine_mengembalikan_null_bila_tidak_ada_gejala(): void
    {
        $engine = app(DiagnosaEngine::class);

        $this->assertNull($engine->cbr([]));
        $this->assertNull($engine->cf([]));
    }

    private function buatGejala(string $kode, float $bobot): Gejala
    {
        return Gejala::create([
            'kode_gejala' => $kode,
            'nama_gejala' => 'Gejala '.$kode,
            'bobot_cbr' => $bobot,
        ]);
    }

    private function buatPenyakit(string $nama): PenyakitHama
    {
        return PenyakitHama::create([
            'nama_penyakit' => $nama,
            'jenis' => 'penyakit',
        ]);
    }

    /**
     * @param  array<int, Gejala>  $gejala
     */
    private function pasangBasis(PenyakitHama $penyakit, array $gejala, float $cfPakar = 0.8): void
    {
        foreach ($gejala as $g) {
            $penyakit->basisGejala()->attach($g->id, ['cf_pakar' => $cfPakar]);
        }
    }
}
