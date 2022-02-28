<?php

namespace App\Models;

use App\Models\Audit;
use App\Models\Dosen;
use App\Models\HasilAudit;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_usul' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'anggotas', 'proposal_id', 'nidn')->withPivot('isLeader');
    }

    public function reviewer()
    {
        return $this->belongsToMany(User::class, 'audits', 'proposal_id', 'user_id')->withPivot('id', 'status');
    }

    public function hasilAudit()
    {
        return $this->hasManyThrough(HasilAudit::class, Audit::class);
    }

    public function scopeFilter($query, array $filters)
    {
        //  ?? new feature in php 7 untuk gabungan isset dan ternary operator
        // cari data proposal berdasarkan filter fakultas
        $query->when($filters['faculty_id'] ?? false, function ($query, $faculty_id) {
            return $query->whereHas('prodi', function ($query) use ($faculty_id) {
                $query->where('faculty_id', $faculty_id);
            });
        });

        // cari data proposal berdasarkan filter tahun
        $query->when($filters['tahun_usul'] ?? false, function ($query, $tahun_usul) {
            return $query->whereYear('tanggal_usul', $tahun_usul);
        });
    }
}
