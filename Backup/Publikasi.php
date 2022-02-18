<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];
    protected $fillable = ['judul_jurnal', 'nama_artikel', 'jenis_jurnal', 'path_jurnal', 'user_id', 'proposal_id', 'tanggal_upload'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
