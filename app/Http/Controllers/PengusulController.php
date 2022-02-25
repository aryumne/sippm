<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class PengusulController extends Controller
{
    public function index()
    {
        $title = "Dashboard Pengusul";
        $notifications = Schedule::whereIn('jadwal_id', [1, 2, 3])->get();
        return view('pengusul.dashboard-pengusul', [
            'title' => $title,
            'notifications' => $notifications,
        ]);
    }

}
