<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanKejadianController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// === ENDPOINT PUBLIK ===
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// === ENDPOINT TERLINDUNGI (WAJIB LOGIN) ===
Route::middleware('auth:sanctum')->group(function () {

    // --- Rute untuk Semua Pengguna Terotentikasi ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user', [AuthController::class, 'updateProfile']);
    
    Route::apiResource('laporan', LaporanKejadianController::class);

    // --- Rute KHUSUS untuk Admin ---
    // Middleware 'admin' dihapus dari sini.
    // Keamanan sekarang sepenuhnya ditangani oleh Policy di dalam AdminController.
    Route::prefix('admin')->group(function () {
        Route::get('/laporan', [AdminController::class, 'getAllLaporan']);
        Route::get('/laporan/{laporan}', [AdminController::class, 'showLaporan']);
        Route::delete('/laporan/{laporan}', [AdminController::class, 'destroyLaporan']);
        Route::patch('/laporan/{laporan}/status', [AdminController::class, 'updateStatus']);
        Route::get('/pelapor', [AdminController::class, 'listReporters']);
        Route::get('/pelapor/{user}/laporan', [AdminController::class, 'getLaporanByUser']);
        Route::get('/users/{user}', [AdminController::class, 'showUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword']);
    });
});
