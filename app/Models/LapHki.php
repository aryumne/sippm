<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Jenis_hki;
use App\Models\TimExternHki;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapHki extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_hki()
    {
        return $this->belongsTo(Jenis_hki::class);
    }

    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_hkis', 'lap_hki_id', 'nidn')->withPivot('isLeader');
    }

    public function timExtern()
    {
        return $this->hasMany(TimExternHki::class, 'lap_hki_id');
    }
}
