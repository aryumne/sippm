<?php

namespace App\Models;

use App\Models\Audit;
use App\Models\Dosen;
use App\Models\HasilAudit;
use App\Models\LapKemajuan;
use App\Models\Proposal;
use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nidn',
        'email',
        'password',
        'path_foto',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn');
    }

    public function hasilAudit()
    {
        return $this->hasManyThrough(HasilAudit::class, Audit::class);
    }

    public function proposal()
    {
        return $this->belongsToMany(Proposal::class, 'audits')->withPivot('id', 'status');
    }

    public function kemajuan()
    {
        return $this->belongsToMany(LapKemajuan::class, 'monevs')->withPivot('id', 'status');
    }
}
