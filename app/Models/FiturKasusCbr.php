<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiturKasusCbr extends Model
{
    //
    protected $table = 'fitur_kasus_cbr';

    protected $fillable = [
        'kasus_cbr_id',
        'gejala_id',
        'nilai'
    ];

    public function kasus()
    {
        return $this->belongsTo(KasusCbr::class, 'kasus_cbr_id');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
