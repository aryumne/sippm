<?php

namespace App\Models;

use App\Models\LapPublikasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimExternPublikasi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lapPublikasi()
    {
        return $this->belongsTo(LapPublikasi::class);
    }
}
