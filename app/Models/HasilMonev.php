<?php

namespace App\Models;

use App\Models\Monev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilMonev extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'luaran_wajib' => 'array',
        'luaran_tambahan' => 'array',
        'kesesuaian' => 'array',
    ];

    public function monev()
    {
        return $this->belongsTo(Monev::class);
    }
}
