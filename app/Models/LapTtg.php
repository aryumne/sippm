<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\TimExternTtg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapTtg extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_ttgs', 'lap_ttg_id', 'nidn')->withPivot('isLeader');
    }

    public function timExtern()
    {
        return $this->hasMany(TimExternTtg::class, 'lap_ttg_id');
    }
}
