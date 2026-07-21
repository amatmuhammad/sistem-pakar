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

    public function penyakitHama()
    {
        return $this->belongsToMany(
            PenyakitHama::class,
            'basis_penyakit_gejala',
            'gejala_id',
            'penyakit_hama_id'
        );
    }
}
