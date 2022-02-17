<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LapAkhirController;
use App\Http\Controllers\LapKemajuanController;
use App\Http\Controllers\PengusulController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReviewerController;
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
    return redirect()->intended('login');
});

Route::group(['prefix' => 'proposal', 'middleware' => ['auth', 'verified', 'isAdminOrPengusul', 'prevent-back-history']], function () {
    Route::resource('/usulan', ProposalController::class)->except(['create', 'destroy']);
    Route::resource('/laporan-kemajuan', LapKemajuanController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('/laporan-akhir', LapAkhirController::class)->except(['create', 'show', 'edit', 'destroy']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'isAdmin', 'prevent-back-history']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
});

Route::group(['prefix' => 'pengusul', 'middleware' => ['auth', 'verified', 'isPengusul', 'prevent-back-history']], function () {
    Route::get('/', [PengusulController::class, 'index'])->name('pengusul.dashboard');
    Route::get('/luaran/publikasi', [PengusulController::class, 'publikasi'])->name('pengusul.luaran.publikasi');
    Route::get('/luaran/haki', [PengusulController::class, 'haki'])->name('pengusul.luaran.haki');
    Route::get('/luaran/buku', [PengusulController::class, 'buku'])->name('pengusul.luaran.buku');
    Route::get('/luaran/ttg', [PengusulController::class, 'ttg'])->name('pengusul.luaran.ttg');
    Route::get('/kegiatan/penelitian', [PengusulController::class, 'penelitian'])->name('pengusul.kegiatan.penelitian');
    Route::get('/kegiatan/pengabdian', [PengusulController::class, 'pengabdian'])->name('pengusul.kegiatan.pengabdian');
});
Route::group(['prefix' => 'reviewer', 'middleware' => ['auth', 'verified', 'isReviewer', 'prevent-back-history']], function () {
    Route::get('/', [ReviewerController::class, 'index'])->name('reviewer.dashboard');
});

require __DIR__ . '/auth.php';
