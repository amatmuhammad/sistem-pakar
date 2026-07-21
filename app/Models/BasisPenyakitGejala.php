<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasisPenyakitGejala extends Model
{
    use HasFactory;

    protected $table = 'basis_penyakit_gejala';

    protected $fillable = [
        'penyakit_hama_id',
        'gejala_id',
    ];

    /**
     * Relasi ke PenyakitHama
     */
    public function penyakitHama()
    {
        return $this->belongsTo(PenyakitHama::class, 'penyakit_hama_id');
    }

    /**
     * Relasi ke Gejala
     */
    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'gejala_id');
    }
}