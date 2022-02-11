<?php

namespace App\Models;

use App\Models\Jabatan;
use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $primaryKey = 'nidn';

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
