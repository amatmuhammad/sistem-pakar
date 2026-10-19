<?php

namespace Tests\Feature;

use App\Models\KasusCbr;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_grafik_menggunakan_tanggal_diagnosa_bukan_created_at(): void
    {
        $user = User::factory()->create();

        // Kasus lama: tanggal diagnosa 3 hari lalu, tapi baris dibuat hari ini.
        $kasusLama = new KasusCbr([
            'user_id' => $user->id,
            'tanggal' => now()->subDays(3),
        ]);
        $kasusLama->created_at = now();
        $kasusLama->updated_at = now();
        $kasusLama->save();

        // Kasus hari ini.
        KasusCbr::create([
            'user_id' => $user->id,
            'tanggal' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();

        $chart = $response->viewData('diagnosaPerTanggal');

        $this->assertCount(7, $chart['labels']);
        $this->assertCount(7, $chart['total']);

        // 6 hari pertama kosong, hari ke-3 berisi 1, hari terakhir berisi 1.
        $this->assertSame(1, $chart['total'][3]);
        $this->assertSame(1, $chart['total'][6]);
        $this->assertSame(2, array_sum($chart['total']));
    }

    public function test_hari_tanpa_diagnosa_menampilkan_nol(): void
    {
        $user = User::factory()->create();

        KasusCbr::create([
            'user_id' => $user->id,
            'tanggal' => now(),
        ]);

        $chart = $this->actingAs($user)->get(route('dashboard'))->viewData('diagnosaPerTanggal');

        $this->assertSame([0, 0, 0, 0, 0, 0, 1], $chart['total']);
    }

    public function test_total_diagnosa_sama_dengan_jumlah_kasus(): void
    {
        $user = User::factory()->create();

        KasusCbr::create(['user_id' => $user->id, 'tanggal' => now()]);
        KasusCbr::create(['user_id' => $user->id, 'tanggal' => now()->subDay()]);

        $data = $this->actingAs($user)->get(route('dashboard'))->viewData();

        $this->assertSame(2, $data['totalKasus']);
        $this->assertSame(2, $data['totalDiagnosa']);
    }
}
