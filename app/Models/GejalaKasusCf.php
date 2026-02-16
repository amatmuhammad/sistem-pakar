<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GejalaKasusCf extends Model
{
    //
    protected $table = 'gejala_kasus_cf';

    protected $fillable = [
        'kasus_cf_id',
        'gejala_id',
        'nilai_cf_user'
    ];

    public function kasus()
    {
        return $this->belongsTo(KasusCf::class, 'kasus_cf_id');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
