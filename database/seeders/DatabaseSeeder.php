<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            GejalaSeeder::class,
            PenyakitHamaSeeder::class,
            AturanCfSeeder::class,
            UserSeeder::class,
            // Basis-knowledge harus siap lebih dulu karena seeder riwayat diagnosa
            // memakai DiagnosaEngine yang bergantung pada tabel basis_penyakit_gejala.
            BasisPenyakitGejalaSeeder::class,
            KasusCbrSeeder::class,
            // BasisPenyakitGejalaCfSeeder::class,
        ]);
    }
}
