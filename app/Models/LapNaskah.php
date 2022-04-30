<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Peruntukan;
use App\Models\TimExternNaskah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapNaskah extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function peruntukan()
    {
        return $this->belongsTo(Peruntukan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_naskahs', 'lap_naskah_id', 'nidn')->withPivot('isLeader');
    }

    public function timExtern()
    {
        return $this->hasMany(TimExternNaskah::class, 'lap_naskah_id');
    }
}
