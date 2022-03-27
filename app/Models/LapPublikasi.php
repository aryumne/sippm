<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Jenis_jurnal;
use App\Models\TimExternPublikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapPublikasi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_jurnal()
    {
        return $this->belongsTo(Jenis_jurnal::class);
    }

    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_publikasis', 'lap_publikasi_id', 'nidn')->withPivot('isLeader');
    }

    public function timExtern()
    {
        return $this->hasMany(TimExternPublikasi::class, 'lap_publikasi_id');
    }

}
