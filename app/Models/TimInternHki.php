<?php

namespace App\Models;

use App\Models\LapHki;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimInternHki extends Model
{
    use HasFactory;
    protected $guarded= ['id'];

    public function lapHki()
    {
        return $this->belongsTo(LapHki::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }
}
