<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\LapBuku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimInternBuku extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapBuku()
    {
        return $this->belongsTo(LapBuku::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }
}
