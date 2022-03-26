<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\LapPublikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimInternPublikasi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapPublikasi()
    {
        return $this->belongsTo(LapPublikasi::class);
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }
}
