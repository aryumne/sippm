<?php

namespace App\Models;

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
