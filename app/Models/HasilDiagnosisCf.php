<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilDiagnosisCf extends Model
{
    //
    protected $table = 'hasil_diagnosis_cf';

    protected $fillable = [
        'kasus_cf_id',
        'penyakit_hama_id',
        'cf_final'
    ];

    public function kasus()
    {
        return $this->belongsTo(KasusCf::class, 'kasus_cf_id');
    }

    public function penyakit()
    {
        return $this->belongsTo(PenyakitHama::class, 'penyakit_hama_id');
    }
}
