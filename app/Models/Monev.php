<?php

namespace App\Models;

use App\Models\HasilMonev;
use App\Models\LapKemajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monev extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kemajuan()
    {
        return $this->belongsTo(LapKemajuan::class, 'lap_kemajuan_id');
    }

    public function hasil()
    {
        return $this->hasOne(HasilMonev::class);
    }
}
