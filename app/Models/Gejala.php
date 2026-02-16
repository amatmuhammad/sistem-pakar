<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    //
    protected $table = 'gejala';

    protected $fillable = [
        'kode_gejala',
        'nama_gejala',
        'bobot_cbr'
    ];

    public function fiturKasusCbr()
    {
        return $this->hasMany(FiturKasusCbr::class);
    }

    public function gejalaKasusCf()
    {
        return $this->hasMany(GejalaKasusCf::class);
    }

    public function aturanCf()
    {
        return $this->hasMany(AturanCf::class);
    }
}
