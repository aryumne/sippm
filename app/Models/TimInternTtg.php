<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\LapTtg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimInternTtg extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapTtg()
    {
        return $this->belongsTo(LapTtg::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }
}
