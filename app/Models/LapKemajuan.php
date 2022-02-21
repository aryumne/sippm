<?php

namespace App\Models;

use App\Models\HasilMonev;
use App\Models\Monev;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapKemajuan extends Model
{
    use HasFactory;
    protected $fillable = ['proposal_id', 'user_id', 'tanggal_upload', 'path_kemajuan'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsToMany(User::class, 'monevs', 'lap_kemajuan_id', 'user_id')->withPivot('status');
    }

    public function hasilMonev()
    {
        return $this->hasOneThrough(HasilMonev::class, Monev::class);
    }
}
