<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyakitHama extends Model
{
    //
    protected $table = 'penyakit_hama';

    protected $fillable = [
        'nama_penyakit',
        'jenis',
        'deskripsi',
        'solusi'
    ];

    public function hasilCbr()
    {
        return $this->hasMany(HasilDiagnosisCbr::class, 'penyakit_id');
    }

    public function hasilCf()
    {
        return $this->hasMany(HasilDiagnosisCf::class, 'penyakit_id');
    }

    public function aturanCf()
    {
        return $this->hasMany(AturanCf::class, 'penyakit_id');
    }

    public function basisGejala()
    {
        return $this->belongsToMany(
            Gejala::class,              // model tujuan
            'basis_penyakit_gejala',    // nama pivot table
            'penyakit_hama_id',         // foreign key dari model ini di pivot
            'gejala_id'                 // foreign key dari model tujuan di pivot
        );
    }
}
