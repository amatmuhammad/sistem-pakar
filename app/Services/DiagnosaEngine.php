<?php

namespace App\Services;

use App\Models\Gejala;
use App\Models\PenyakitHama;

/**
 * Mesin inferensi Case Based Reasoning (CBR) dan Certainty Factor (CF).
 *
 * Dipakai oleh DiagnosaController untuk proses diagnosa dan oleh seeder data
 * demo, sehingga hasil yang tersimpan selalu konsisten dengan perhitungan mesin.
 */
class DiagnosaEngine
{
    /** Ambang minimum keyakinan CF agar hasil dianggap sah. */
    public const CF_THRESHOLD = 0.5;

    /** Nilai keyakinan user untuk setiap gejala yang dipilih (default: sangat yakin). */
    public const NILAI_CF_USER = 1.0;

    /**
     * CBR: kasus baru dicari penyakit dengan similarity tertinggi.
     *
     * similarity = (bobot gejala yang cocok) / (bobot seluruh gejala basis penyakit)
     *
     * @param  array<int, int|string>  $gejalaIds
     * @return array{penyakit_hama_id: int, similarity_final: float}|null
     */
    public function cbr(array $gejalaIds): ?array
    {
        $gejalaIds = $this->normalisasiGejala($gejalaIds);

        if ($gejalaIds === []) {
            return null;
        }

        $penyakitList = PenyakitHama::with('basisGejala')->get();

        // Bobot seluruh gejala basis diambil dalam satu query (hindari N+1).
        $idGejala = $penyakitList
            ->pluck('basisGejala')
            ->flatten()
            ->pluck('id')
            ->merge($gejalaIds)
            ->unique()
            ->values();
        $bobot = Gejala::whereIn('id', $idGejala)->pluck('bobot_cbr', 'id');

        $penyakitTerbaik = null;
        $similarityTertinggi = 0.0;

        foreach ($penyakitList as $penyakit) {
            $gejalaPenyakit = $penyakit->basisGejala->pluck('id');

            if ($gejalaPenyakit->isEmpty()) {
                continue;
            }

            $totalBobot = $gejalaPenyakit->sum(fn ($id) => (float) ($bobot[$id] ?? 0));

            if ($totalBobot <= 0) {
                continue;
            }

            $bobotCocok = $gejalaPenyakit
                ->filter(fn ($id) => in_array((int) $id, $gejalaIds, true))
                ->sum(fn ($id) => (float) ($bobot[$id] ?? 0));

            $similarity = $bobotCocok / $totalBobot;

            if ($similarity > $similarityTertinggi) {
                $similarityTertinggi = $similarity;
                $penyakitTerbaik = $penyakit;
            }
        }

        if ($penyakitTerbaik === null || $similarityTertinggi <= 0) {
            return null;
        }

        return [
            'penyakit_hama_id' => $penyakitTerbaik->id,
            'similarity_final' => $similarityTertinggi,
        ];
    }

    /**
     * CF: keyakinan tiap gejala digabung per penyakit (Certainty Factor).
     *
     * cfCombine = cfCombine + (cfGejala * (1 - cfCombine))
     *
     * @param  array<int, int|string>  $gejalaIds
     * @return array{penyakit_hama_id: int, cf_final: float}|null
     */
    public function cf(array $gejalaIds): ?array
    {
        $gejalaIds = $this->normalisasiGejala($gejalaIds);

        if ($gejalaIds === []) {
            return null;
        }

        $cfPenyakit = [];

        foreach (PenyakitHama::with('basisGejalaPivot')->get() as $penyakit) {
            $cfCombine = 0.0;

            // Urutan combine mengikuti urutan gejala yang dipilih user.
            foreach ($gejalaIds as $gejalaId) {
                $basis = $penyakit->basisGejalaPivot->firstWhere('id', $gejalaId);

                if (! $basis || ! isset($basis->pivot->cf_pakar)) {
                    continue;
                }

                $cfGejala = (float) $basis->pivot->cf_pakar * self::NILAI_CF_USER;
                $cfCombine = $cfCombine == 0.0
                    ? $cfGejala
                    : $cfCombine + $cfGejala * (1 - $cfCombine);
            }

            if ($cfCombine > 0) {
                $cfPenyakit[$penyakit->id] = $cfCombine;
            }
        }

        if ($cfPenyakit === []) {
            return null;
        }

        arsort($cfPenyakit);
        $penyakitId = (int) array_key_first($cfPenyakit);
        $cfFinal = $cfPenyakit[$penyakitId];

        if ($cfFinal < self::CF_THRESHOLD) {
            return null;
        }

        return [
            'penyakit_hama_id' => $penyakitId,
            'cf_final' => $cfFinal,
        ];
    }

    /**
     * Normalisasi daftar id gejala: unik, numerik, dan tidak kosong.
     *
     * @param  array<int, int|string>  $gejalaIds
     * @return array<int, int>
     */
    private function normalisasiGejala(array $gejalaIds): array
    {
        return array_values(array_unique(array_map('intval', $gejalaIds)));
    }
}
