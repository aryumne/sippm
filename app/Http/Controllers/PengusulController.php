<?php

namespace App\Http\Controllers;

class PengusulController extends Controller
{
    public function index()
    {
        $title = "Dashboard Pengusul";
        return view('pengusul.dashboard-pengusul', ['title' => $title]);
    }

}
