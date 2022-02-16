<?php

namespace App\Models;

use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
