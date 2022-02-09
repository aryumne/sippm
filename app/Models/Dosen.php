<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'jabatan_id',
        'prodi_id',
        'handphone',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
