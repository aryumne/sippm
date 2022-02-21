<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Haki extends Model
{
    use HasFactory;

    protected $fillable = ['proposal_id', 'user_id', 'jenis_haki', 'path_haki', 'tanggal_upload'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}