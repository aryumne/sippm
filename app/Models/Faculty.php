<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Kegiatan;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function prodi()
    {
        return $this->belongsToMany(Prodi::class);
    }

    public function proposal()
    {
        return $this->hasManyThrough(Proposal::class, Prodi::class);
    }

    public function kegiatan()
    {
        return $this->hasManyThrough(Kegiatan::class, Prodi::class);
    }
}
