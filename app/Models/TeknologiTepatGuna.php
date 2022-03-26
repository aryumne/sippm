<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeknologiTepatGuna extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];
    protected $fillable = ['bidang', 'path_ttg', 'user_id', 'proposal_id', 'tanggal_upload'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
