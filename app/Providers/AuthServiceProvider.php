<?php

namespace App\Providers;

use App\Models\Schedule;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //ambil semua schedule
        // $schedules = Schedule::all();
        // //dapatkan waktu sekarang
        // date_default_timezone_set('Asia/Jayapura');
        // $getDateTimeNow = date("Y-m-d H:i:s");
        // foreach ($schedules as $schd) {
        //     if ($getDateTimeNow >= $schd->started_at && $getDateTimeNow <= $schd->finished_at) {
        //         Gate::define($schd->jadwal->slug_jadwal, function () {
        //             return Auth::user()->role_id <= 3;
        //         });
        //     } else {
        //         Gate::define($schd->jadwal->slug_jadwal, function () {
        //             return Auth::user()->role_id == 1;
        //         });
        //     }
        // }
    }
}
