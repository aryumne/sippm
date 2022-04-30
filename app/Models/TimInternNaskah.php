<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\LapNaskah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimInternNaskah extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function lapNaskah()
    {
        return $this->belongsTo(LapNaskah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }
}
