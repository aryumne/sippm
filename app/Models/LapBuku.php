<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dosen;
use App\Models\TimExternBuku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapBuku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function timIntern()
    {
        return $this->belongsToMany(Dosen::class, 'tim_intern_bukus', 'lap_buku_id', 'nidn')->withPivot('isLeader');
    }

    public function timExtern()
    {
        return $this->hasMany(TimExternBuku::class, 'lap_buku_id');
    }
}
