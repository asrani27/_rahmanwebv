<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\LokasiController;

// Redirect root to login
Route::get('/', function () {
    if (Auth::check()) {
        // Redirect based on user role
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == 'skpd') {
            return redirect()->route('skpd.dashboard');
        } elseif (Auth::user()->role == 'pegawai') {
            return redirect()->route('pegawai.dashboard');
        }
    }

    return view('auth.login');
});

// Authentication routes (guest only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Logout route (can be accessed by authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin dashboard route
    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    // SKPD dashboard route
    Route::get('/skpd/dashboard', [SkpdController::class, 'dashboard'])->name('skpd.dashboard');

    // SKPD profil routes
    Route::get('/skpd/profil', [SkpdController::class, 'profil'])->name('skpd.profil');
    Route::post('/skpd/profil', [SkpdController::class, 'updateProfil'])->name('skpd.update.profil');
    Route::post('/skpd/profil/password', [SkpdController::class, 'updatePassword'])->name('skpd.update.password');

    // SKPD lokasi routes
    Route::get('/skpd/lokasi', [LokasiController::class, 'skpdIndex'])->name('skpd.lokasi.index');
    Route::get('/skpd/lokasi/create', [LokasiController::class, 'skpdCreate'])->name('skpd.lokasi.create');
    Route::post('/skpd/lokasi', [LokasiController::class, 'skpdStore'])->name('skpd.lokasi.store');
    Route::get('/skpd/lokasi/{lokasi}/edit', [LokasiController::class, 'skpdEdit'])->name('skpd.lokasi.edit');
    Route::put('/skpd/lokasi/{lokasi}', [LokasiController::class, 'skpdUpdate'])->name('skpd.lokasi.update');
    Route::delete('/skpd/lokasi/{lokasi}', [LokasiController::class, 'skpdDestroy'])->name('skpd.lokasi.destroy');

    // Pegawai dashboard route
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');

    // SKPD pegawai routes
    Route::get('/skpd/pegawai', [PegawaiController::class, 'skpdIndex'])->name('skpd.pegawai.index');
    Route::get('/skpd/pegawai/create', [PegawaiController::class, 'skpdCreate'])->name('skpd.pegawai.create');
    Route::post('/skpd/pegawai', [PegawaiController::class, 'skpdStore'])->name('skpd.pegawai.store');
    Route::get('/skpd/pegawai/{pegawai}/edit', [PegawaiController::class, 'skpdEdit'])->name('skpd.pegawai.edit');
    Route::put('/skpd/pegawai/{pegawai}', [PegawaiController::class, 'skpdUpdate'])->name('skpd.pegawai.update');
    Route::delete('/skpd/pegawai/{pegawai}', [PegawaiController::class, 'skpdDestroy'])->name('skpd.pegawai.destroy');
    Route::post('/skpd/pegawai/{pegawai}/create-user', [PegawaiController::class, 'createUser'])->name('skpd.pegawai.createUser');
    Route::post('/skpd/pegawai/{pegawai}/reset-password', [PegawaiController::class, 'resetPassword'])->name('skpd.pegawai.resetPassword');

    // Pegawai profil routes
    Route::get('/pegawai/profil', [PegawaiController::class, 'profil'])->name('pegawai.profil');
    Route::post('/pegawai/profil/biodata', [PegawaiController::class, 'updateBiodata'])->name('pegawai.update.biodata');
    Route::post('/pegawai/profil/password', [PegawaiController::class, 'updatePassword'])->name('pegawai.update.password');

    // Admin routes group with /admin prefix
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.index');
        })->name('dashboard');

        // User management routes
        Route::resource('users', UserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        // SKPD management routes
        Route::resource('skpd', SkpdController::class)->names([
            'index' => 'skpd.index',
            'create' => 'skpd.create',
            'store' => 'skpd.store',
            'edit' => 'skpd.edit',
            'update' => 'skpd.update',
            'destroy' => 'skpd.destroy',
        ]);
        Route::post('skpd/{skpd}/create-user', [SkpdController::class, 'createUser'])->name('skpd.create-user');
        Route::post('skpd/{skpd}/reset-password', [SkpdController::class, 'resetPassword'])->name('skpd.reset-password');

        // Pegawai management routes
        Route::resource('pegawai', PegawaiController::class)->names([
            'index' => 'pegawai.index',
            'create' => 'pegawai.create',
            'store' => 'pegawai.store',
            'edit' => 'pegawai.edit',
            'update' => 'pegawai.update',
            'destroy' => 'pegawai.destroy',
        ]);
        Route::post('pegawai/{pegawai}/create-user', [PegawaiController::class, 'createUser'])->name('pegawai.create-user');
        Route::post('pegawai/{pegawai}/reset-password', [PegawaiController::class, 'resetPassword'])->name('pegawai.reset-password');

        // Lokasi Presensi routes
        Route::resource('lokasi', LokasiController::class)->names([
            'index' => 'lokasi.index',
            'create' => 'lokasi.create',
            'store' => 'lokasi.store',
            'edit' => 'lokasi.edit',
            'update' => 'lokasi.update',
            'destroy' => 'lokasi.destroy',
        ]);

        // Lokasi Pegawai management routes
        Route::get('lokasi/{lokasi}/add-pegawai', [LokasiController::class, 'addPegawai'])->name('lokasi.add-pegawai');
        Route::post('lokasi/{lokasi}/store-pegawai', [LokasiController::class, 'storePegawai'])->name('lokasi.store-pegawai');
        Route::delete('lokasi/{lokasi}/remove-pegawai/{pegawai}', [LokasiController::class, 'removePegawai'])->name('lokasi.remove-pegawai');
    });

    // Laporan routes (accessible by all authenticated users)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
});
