<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // protected $fillable = ['judul_kegiatan', 'jenis_kegiatan', 'jumlah_dana', 'tanggal_kegiatan', 'path_kegiatan', 'sumber_id', 'user_id'];
    // protected $table = "kegiatans";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'sumber_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_id');
    }
}
