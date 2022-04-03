<?php

namespace App\Models;

use App\Models\LapHki;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimExternHki extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapHki()
    {
        return $this->belongsTo(LapHki::class);
    }
}
