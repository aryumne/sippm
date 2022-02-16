<?php

namespace App\Models;

use App\Models\User;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LapAkhir extends Model
{
    use HasFactory;

    protected $fillable = ['proposal_id', 'user_id', 'tanggal_upload', 'path_akhir'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
