<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusCbr extends Model
{
    //
    protected $table = 'kasus_cbr';

    protected $fillable = [
        'user_id',
        'tanggal',
        'nilai_similarity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fitur()
    {
        return $this->hasMany(FiturKasusCbr::class, 'kasus_cbr_id');
    }

    public function hasil()
    {
        return $this->hasOne(HasilDiagnosisCbr::class, 'kasus_cbr_id');
    }

}
