<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run()
    {
        $gejala = [
            ['kode_gejala' => 'G01', 'nama_gejala' => 'Daun berlubang tidak beraturan', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G02', 'nama_gejala' => 'Daun tampak tercabik', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G03', 'nama_gejala' => 'Terdapat kotoran ulat pada daun', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G04', 'nama_gejala' => 'Titik tumbuh rusak', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G05', 'nama_gejala' => 'Tanaman tumbuh tidak normal', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G06', 'nama_gejala' => 'Tanaman muda dapat mati', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G07', 'nama_gejala' => 'Lubang kecil pada batang', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G08', 'nama_gejala' => 'Serbuk sisa gerekan pada batang', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G09', 'nama_gejala' => 'Batang mudah patah', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G10', 'nama_gejala' => 'Tanaman layu', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G11', 'nama_gejala' => 'Pertumbuhan terhambat', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G12', 'nama_gejala' => 'Tongkol tidak berkembang sempurna', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G13', 'nama_gejala' => 'Bercak kuning pada daun', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G14', 'nama_gejala' => 'Daun menggulung dan kering', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G15', 'nama_gejala' => 'Tepung putih di permukaan daun', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G16', 'nama_gejala' => 'Bercak coklat keabuan dengan tepi kuning', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G17', 'nama_gejala' => 'Bercak memanjang seperti korek api', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G18', 'nama_gejala' => 'Daun mengering mulai dari ujung', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G19', 'nama_gejala' => 'Pembusukan pada pangkal batang', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G20', 'nama_gejala' => 'Tongkol berlendir dan berbau busuk', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G21', 'nama_gejala' => 'Biji jagung berwarna merah jambu', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G22', 'nama_gejala' => 'Akar dan pangkal batang membusuk', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G23', 'nama_gejala' => 'Tanaman mudah dicabut', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G24', 'nama_gejala' => 'Miselia jamur berwarna putih pada tongkol', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G25', 'nama_gejala' => 'Bercak karat (oranye/kuning) pada daun', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G26', 'nama_gejala' => 'Daun berlubang simetris di sepanjang urat', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G27', 'nama_gejala' => 'Terdapat ulat di dalam batang', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G28', 'nama_gejala' => 'Batang bengkak dan pecah', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G29', 'nama_gejala' => 'Klorosis pada daun muda', 'bobot_cbr' => 1],
            ['kode_gejala' => 'G30', 'nama_gejala' => 'Daun melintir dan kaku', 'bobot_cbr' => 1],
        ];

        DB::table('gejala')->insert($gejala);
    }
}