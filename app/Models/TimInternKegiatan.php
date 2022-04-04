<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimInternKegiatan extends Model
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
