<?php

namespace App\Models;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // protected $fillable = ['judul_kegiatan', 'jenis_kegiatan', 'jumlah_dana', 'tanggal_kegiatan', 'path_kegiatan', 'sumber_id', 'user_id'];
    // protected $table = "kegiatans";

    protected $casts = [
        'tanggal_kegiatan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'sumber_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_id');
    }

    public function scopeFilterPenelitian($query, array $filters)
    {
        //  ?? new feature in php 7 untuk gabungan isset dan ternary operator

        //  ?? new feature in php 7 untuk gabungan isset dan ternary operator
        // cari data proposal berdasarkan filter fakultas
        $query->when($filters['faculty_id'] ?? false, function ($query, $faculty_id) {
            return $query->whereHas('prodi', function ($query) use ($faculty_id) {
                $query->where('faculty_id', $faculty_id);
            });
        });

        // cari data proposal berdasarkan filter tahun
        $query->when($filters['tahun_kegiatan'] ?? false, function ($query, $tahun_kegiatan) {
            return $query->whereYear('tanggal_kegiatan', $tahun_kegiatan);
        });

        // cari data proposal berdasarkan filter sumber dana
        $query->when($filters['sumber_dana'] ?? false, function ($query, $sumber_dana) {
            return $query->whereHas('sumberDana', function ($query) use ($sumber_dana) {
                $query->where('sumber', $sumber_dana);
            });
        });

    }
}
