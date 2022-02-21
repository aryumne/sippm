<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];
    protected $fillable = ['proposal_id', 'user_id', 'path_hki', 'tanggal_upload', 'jenis_hki_id'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_hki()
    {
        return $this->belongsTo(Jenis_hki::class);
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'anggotas', 'proposal_id', 'nidn')->withPivot('isLeader');
    }
}
