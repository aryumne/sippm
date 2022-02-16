<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        $title = "Dashboard Admin";
        return view('admin.dashboard-admin', ['title' => $title]);
    }
}
