<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Anggota;
use App\Models\Jabatan;
use App\Models\Kegiatan;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $primaryKey = 'nidn';
    public $incrementing = false;

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

    public function proposal()
    {
        return $this->belongsToMany(Proposal::class, 'anggotas', 'nidn')->withPivot('isLeader');
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'anggota_kegiatans', 'nidn');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'nidn', 'nidn');
    }
}
