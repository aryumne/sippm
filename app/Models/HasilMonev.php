<?php

namespace App\Models;

use App\Models\Monev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilMonev extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function audit()
    {
        return $this->belongsTo(Monev::class);
    }
}
