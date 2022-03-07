<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\HkiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LapAkhirController;
use App\Http\Controllers\LapKemajuanController;
use App\Http\Controllers\PengusulController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\TeknologiTepatGunaController;
use App\Http\Controllers\UserController;
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
    return view('auth.index');
})->middleware('guest');

//ROUTE KHUSUS ADMIN
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'isAdmin', 'prevent-back-history']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    //akun reviewer
    Route::get('/reviewers', [AdminController::class, 'reviewers'])->name('admin.reviewers.index');
    Route::post('/storeReviewer', [AdminController::class, 'storeReviewer'])->name('admin.reviewers.store');
    //penilaian
    Route::get('/audits', [AdminController::class, 'audits'])->name('adminpenilaian.audits.index');
    Route::get('/hasilAudits', [AdminController::class, 'hasilAudits'])->name('adminpenilaian.audits.hasil');
    Route::post('/auditStore', [AdminController::class, 'auditStore'])->name('adminpenilaian.audits.store');
    Route::put('/auditUpdate/{id}', [AdminController::class, 'auditUpdateStatus'])->name('adminpenilaian.audits.update');
    //monitoring dan evaluasi (MONEV)
    Route::get('/monevs', [AdminController::class, 'monevs'])->name('adminpenilaian.monevs.index');
    Route::get('/hasilMonevs', [AdminController::class, 'hasilMonevs'])->name('adminpenilaian.monevs.hasil');
    Route::post('/monevStore', [AdminController::class, 'monevStore'])->name('adminpenilaian.monevs.store');
    Route::put('/monevUpdate/{id}', [AdminController::class, 'monevUpdateStatus'])->name('adminpenilaian.monevs.update');
    //penjadwalan
    Route::resource('/schedule', ScheduleController::class)->only(['index', 'update']);
    //Dosen
    Route::resource('/dosen', DosenController::class)->except(['edit', 'destroy']);
    Route::post('/import', [DosenController::class, 'import'])->name('importDosen');
    //Prodi
    Route::resource('/prodi', ProdiController::class)->only(['store', 'update']);
    Route::resource('/faculty', FacultyController::class)->only(['index', 'store', 'update']);
    //Jabatan
    Route::resource('/jabatan', JabatanController::class)->only(['index', 'store', 'update']);
    //Sumber Dana
    Route::resource('/sumberDana', SumberDanaController::class)->only(['index', 'store', 'update']);
});

//ROUTE KHUSUS PENGUSUL
Route::group(['prefix' => 'pengusul', 'middleware' => ['auth', 'verified', 'isPengusul', 'prevent-back-history']], function () {
    Route::get('/', [PengusulController::class, 'index'])->name('pengusul.dashboard');
    Route::get('/luaran/publikasi', [PengusulController::class, 'publikasi'])->name('pengusul.luaran.publikasi');
    Route::get('/luaran/haki', [PengusulController::class, 'haki'])->name('pengusul.luaran.haki');
    Route::get('/luaran/buku', [PengusulController::class, 'buku'])->name('pengusul.luaran.buku');
    Route::get('/luaran/ttg', [PengusulController::class, 'ttg'])->name('pengusul.luaran.ttg');
    Route::get('/kegiatan/penelitian', [PengusulController::class, 'penelitian'])->name('pengusul.kegiatan.penelitian');
    Route::get('/kegiatan/pengabdian', [PengusulController::class, 'pengabdian'])->name('pengusul.kegiatan.pengabdian');
});

//ROUTE KHUSUS REVIEWER
Route::group(['prefix' => 'reviewer', 'middleware' => ['auth', 'verified', 'isReviewer', 'prevent-back-history']], function () {
    Route::get('/', [ReviewerController::class, 'index'])->name('reviewer.dashboard');
    //Penilaian Proposal
    Route::get('/audit', [ReviewerController::class, 'auditProposals'])->name('reviewer.audit.proposals');
    Route::get('/formAudit/{id}', [ReviewerController::class, 'formAudit'])->name('reviewer.audit.form');
    Route::post('/storeAudit/{id}', [ReviewerController::class, 'storeAudit'])->name('reviewer.audit.store');
    Route::get('/editHasilAudit/{id}', [ReviewerController::class, 'editFormAudit'])->name('reviewer.audit.edit');
    Route::put('/updateAudit/{id}', [ReviewerController::class, 'updateAudit'])->name('reviewer.audit.update');
    //Monev Laporan Kemajuan
    Route::get('/monev', [ReviewerController::class, 'monevKemajuan'])->name('reviewer.monev.kemajuan');
    Route::get('/formMonev/{id}', [ReviewerController::class, 'formMonev'])->name('reviewer.monev.form');
    Route::post('/storeMonev/{id}', [ReviewerController::class, 'storeMonev'])->name('reviewer.monev.store');
    Route::get('/editHasilMonev/{id}', [ReviewerController::class, 'editFormMonev'])->name('reviewer.monev.edit');
    Route::put('/updateMonev/{id}', [ReviewerController::class, 'updateMonev'])->name('reviewer.monev.update');
});

//ROUTE KHUSUS ADMIN DAN PENGUSUL
Route::group(['prefix' => 'proposal', 'middleware' => ['auth', 'verified', 'isAdminOrPengusul', 'prevent-back-history']], function () {
    Route::resource('/usulan', ProposalController::class);
    Route::resource('/laporan-kemajuan', LapKemajuanController::class);
    Route::resource('/laporan-akhir', LapAkhirController::class);
    Route::resource('/publikasi', PublikasiController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/hki', HkiController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/usulan', ProposalController::class)->except(['create', 'destroy']);
    Route::resource('/laporan-kemajuan', LapKemajuanController::class)->except(['create', 'edit', 'destroy']);
    Route::resource('/laporan-akhir', LapAkhirController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/buku', BukuController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/ttg', TeknologiTepatGunaController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('/kegiatan', KegiatanController::class)->except(['index', 'show', 'edit', 'destroy']);
    Route::get('/kegiatan/{kegiatan}', [KegiatanController::class, 'index'])->name('kegiatan.index');
});

//ROUTE UNTUK SEMUA YANG LOGIN
Route::group(['prefix' => 'profile', 'middleware' => ['auth', 'verified', 'prevent-back-history']], function () {
    Route::resource('/user', UserController::class)->only(['update']);
    Route::get('/editProfile', [DosenController::class, 'edit'])->name('editProfile');
    Route::put('/updateProfile/{id}', [DosenController::class, 'updateProfile'])->name('updateProfile');
});

require __DIR__ . '/auth.php';
