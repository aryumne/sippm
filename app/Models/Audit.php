<?php

namespace App\Models;

use App\Models\User;
use App\Models\Proposal;
use App\Models\HasilAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function hasil()
    {
        return $this->hasOne(HasilAudit::class);
    }
}

