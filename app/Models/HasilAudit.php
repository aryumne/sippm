<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilAudit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

}
