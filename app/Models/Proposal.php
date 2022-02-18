<?php

namespace App\Models;

use App\Models\Audit;
use App\Models\Dosen;
use App\Models\HasilAudit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'anggotas', 'proposal_id', 'nidn')->withPivot('isLeader');
    }

    public function reviewer()
    {
        return $this->belongsToMany(User::class, 'audits', 'proposal_id', 'user_id')->withPivot('status');
    }

    public function hasilAudit()
    {
        return $this->hasManyThrough(HasilAudit::class, Audit::class);
    }
}
