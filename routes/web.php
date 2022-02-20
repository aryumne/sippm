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

    //akun reviewer
    Route::get('/reviewers', [AdminController::class, 'reviewers'])->name('admin.reviewers.index');
    Route::post('/storeReviewer', [AdminController::class, 'storeReviewer'])->name('admin.reviewers.store');
    //penilaian
    Route::get('/audits', [AdminController::class, 'audits'])->name('adminpenilaian.audits.index');
    Route::post('/auditStore', [AdminController::class, 'auditStore'])->name('adminpenilaian.audits.store');
    Route::put('/auditUpdate/{id}', [AdminController::class, 'auditUpdateStatus'])->name('adminpenilaian.audits.update');
    //monitoring dan evaluasi (MONEV)
    Route::get('/monevs', [AdminController::class, 'monevs'])->name('adminpenilaian.monevs.index');
    Route::post('/monevStore', [AdminController::class, 'monevStore'])->name('adminpenilaian.monevs.store');
    Route::put('/monevUpdate/{id}', [AdminController::class, 'monevUpdateStatus'])->name('adminpenilaian.monevs.update');
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

    Route::get('/audit', [ReviewerController::class, 'auditProposals'])->name('reviewer.audit.proposals');
    Route::get('/formAudit/{id}', [ReviewerController::class, 'formAudit'])->name('reviewer.audit.form');
    Route::post('/storeAudit/{id}', [ReviewerController::class, 'storeAudit'])->name('reviewer.audit.store');

    Route::get('/monev', [ReviewerController::class, 'monevKemajuan'])->name('reviewer.monev.kemajuan');
    Route::get('/formMonev/{id}', [ReviewerController::class, 'formMonev'])->name('reviewer.monev.form');
    Route::post('/storeMonev/{id}', [ReviewerController::class, 'storeMonev'])->name('reviewer.monev.store');
});

require __DIR__ . '/auth.php';
