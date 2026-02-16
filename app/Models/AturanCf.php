<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanCf extends Model
{
    //
    protected $table = 'aturan_cf';

    protected $fillable = [
        'penyakit_id',
        'gejala_id',
        'mb',
        'md'
    ];

    public function penyakit()
    {
        return $this->belongsTo(PenyakitHama::class, 'penyakit_id');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
