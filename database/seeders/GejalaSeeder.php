<?php

namespace Database\Seeders;

use App\Models\Gejala;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['kode_gejala'=>'G01','nama_gejala'=>'Daun menguning','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G02','nama_gejala'=>'Daun terdapat bercak coklat','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G03','nama_gejala'=>'Daun terdapat pustul karat','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G04','nama_gejala'=>'Daun berlubang','bobot_cbr'=>0.7],
            ['kode_gejala'=>'G05','nama_gejala'=>'Daun rusak seperti transparan','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G06','nama_gejala'=>'Pertumbuhan tanaman terhambat','bobot_cbr'=>0.7],
            ['kode_gejala'=>'G07','nama_gejala'=>'Tanaman layu','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G08','nama_gejala'=>'Batang busuk','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G09','nama_gejala'=>'Batang berlubang','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G10','nama_gejala'=>'Batang mudah patah','bobot_cbr'=>0.7],
            ['kode_gejala'=>'G11','nama_gejala'=>'Terdapat ulat pada pucuk','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G12','nama_gejala'=>'Terdapat larva di dalam batang','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G13','nama_gejala'=>'Tongkol rusak','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G14','nama_gejala'=>'Biji jagung membusuk','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G15','nama_gejala'=>'Daun bergaris kuning','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G16','nama_gejala'=>'Daun mosaik','bobot_cbr'=>0.8],
            ['kode_gejala'=>'G17','nama_gejala'=>'Tanaman kerdil','bobot_cbr'=>0.7],
            ['kode_gejala'=>'G18','nama_gejala'=>'Daun menggulung','bobot_cbr'=>0.6],
            ['kode_gejala'=>'G19','nama_gejala'=>'Pucuk mati','bobot_cbr'=>0.9],
            ['kode_gejala'=>'G20','nama_gejala'=>'Terdapat lubang kecil pada daun','bobot_cbr'=>0.6],
        ];

        foreach ($data as $item) {
            Gejala::create($item);
        }
    }
}
