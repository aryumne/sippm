<?php

namespace App\Models;

use App\Models\LapTtg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimExternTtg extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapTtg()
    {
        return $this->belongsTo(LapTtg::class);
    }
}
