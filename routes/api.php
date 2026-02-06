<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Authentication Routes for Pegawai (Mobile App)
Route::prefix('v1')->group(function () {
    // Public routes (no authentication required)
    Route::post('/pegawai/login', [AuthController::class, 'apiLogin'])->name('api.pegawai.login');

    // Protected routes (authentication required)
    // Note: In production, you should add proper authentication middleware
    // using Laravel Sanctum or JWT authentication
    Route::middleware([])->group(function () {
        Route::post('/pegawai/logout', [AuthController::class, 'apiLogout'])->name('api.pegawai.logout');
        Route::get('/pegawai/profile', [AuthController::class, 'apiProfile'])->name('api.pegawai.profile');

        // Presensi Routes
        Route::post('/pegawai/presensi/checkin', [PresensiApiController::class, 'checkin'])->name('api.presensi.checkin');
        Route::post('/pegawai/presensi/checkout', [PresensiApiController::class, 'checkout'])->name('api.presensi.checkout');
        Route::get('/pegawai/presensi/history', [PresensiApiController::class, 'history'])->name('api.presensi.history');
        Route::get('/pegawai/presensi/today-status', [PresensiApiController::class, 'todayStatus'])->name('api.presensi.today-status');
        Route::get('/pegawai/presensi/lokasi', [PresensiApiController::class, 'getLokasiByPegawai'])->name('api.presensi.lokasi');
        Route::get('/pegawai/presensi/lokasi-by-id', [PresensiApiController::class, 'getLokasiByPegawaiId'])->name('api.presensi.lokasi-by-id');
    });
});
