<?php

namespace App\Http\Controllers;

class ReviewerController extends Controller
{
    public function index()
    {
        $title = " Dashboard Reviewer";
        return view('reviewer.dashboard-reviewer', ['title' => $title]);
    }
}
