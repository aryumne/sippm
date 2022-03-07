<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnggotaKegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
