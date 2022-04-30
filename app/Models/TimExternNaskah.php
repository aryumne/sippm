<?php

namespace App\Models;

use App\Models\LapNaskah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimExternNaskah extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function lapNaskah()
    {
        return $this->belongsTo(LapNaskah::class);
    }
}
