<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilDiagnosisCbr extends Model
{
    //
    protected $table = 'hasil_diagnosis_cbr';

    protected $fillable = [
        'kasus_cbr_id',
        'penyakit_id',
        'similarity_final'
    ];

    public function kasus()
    {
        return $this->belongsTo(KasusCbr::class, 'kasus_cbr_id');
    }

    public function penyakit()
    {
        return $this->belongsTo(PenyakitHama::class, 'penyakit_id');
    }
}
