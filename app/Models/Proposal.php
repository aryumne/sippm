<?php

namespace App\Models;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pengusul()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'anggotas', 'proposal_id', 'nidn')->withPivot('isLeader');
    }
}
