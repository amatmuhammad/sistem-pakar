<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusCf extends Model
{
    //
    protected $table = 'kasus_cf';

    protected $fillable = [
        'user_id',
        'tanggal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gejala()
    {
        return $this->hasMany(GejalaKasusCf::class, 'kasus_cf_id');
    }

    public function hasil()
    {
        return $this->hasOne(HasilDiagnosisCf::class, 'kasus_cf_id');
    }
}
