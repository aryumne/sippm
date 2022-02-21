<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];
    protected $fillable = ['judul_jurnal', 'nama_artikel', 'path_jurnal', 'user_id', 'proposal_id', 'tanggal_upload', 'jenis_jurnal_id'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_jurnal()
    {
        return $this->belongsTo(Jenis_jurnal::class);
    }

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'anggotas', 'proposal_id', 'nidn')->withPivot('isLeader');
    }
}