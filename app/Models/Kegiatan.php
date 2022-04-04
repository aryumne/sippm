<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\SumberDana;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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

    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_kegiatans', 'kegiatan_id', 'nidn')->withPivot('isLeader');
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
        $query->when($filters['tahun'] ?? false, function ($query, $tahun) {
            return $query->whereYear('tahun', $tahun);
        });

        // cari data proposal berdasarkan filter sumber dana
        $query->when($filters['sumber_dana'] ?? false, function ($query, $sumber_dana) {
            return $query->whereHas('sumberDana', function ($query) use ($sumber_dana) {
                $query->where('sumber', $sumber_dana);
            });
        });

    }
}
