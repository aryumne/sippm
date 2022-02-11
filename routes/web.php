<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'isAdmin', 'prevent-back-history'])->name('dashboard');

Route::get('/pengusul', function () {
    return view('pengusul.dashboard-pengusul');
})->middleware(['auth', 'verified', 'isPengusul', 'prevent-back-history'])->name('pengusul.dashboard');
Route::get('/admin', function () {
    return view('admin.dashboard-admin');
})->middleware(['auth', 'verified', 'isAdmin', 'prevent-back-history'])->name('admin.dashboard');
Route::get('/reviewer', function () {
    return view('reviewer.dashboard-reviewer');
})->middleware(['auth', 'verified', 'isReviewer', 'prevent-back-history'])->name('reviewer.dashboard');

require __DIR__ . '/auth.php';
