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
| Semua route di sini khusus untuk API (mobile app dan admin panel).
| Versi view (web) tetap terpisah di routes/web.php agar tidak bentrok.
|--------------------------------------------------------------------------
*/

// === 🟢 ENDPOINT PUBLIK ===
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// === 🔐 ENDPOINT TERLINDUNGI (HANYA UNTUK PENGGUNA LOGIN) ===
Route::middleware('auth:sanctum')->group(function () {

    // --- 👤 Profil User yang Sedang Login ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Update profil pengguna (Flutter)
    Route::post('/user', [AuthController::class, 'updateProfile']);

    // --- 🧾 LAPORAN KEJADIAN (USER) ---
    // Gunakan controller API yang memulangkan JSON (sudah diperbarui)
    Route::apiResource('laporan', LaporanKejadianController::class)
        ->names([
            'index' => 'api.laporan.index',
            'store' => 'api.laporan.store',
            'show' => 'api.laporan.show',
            'update' => 'api.laporan.update',
            'destroy' => 'api.laporan.destroy',
        ]);

    // --- 👑 RUTE ADMIN ---
    Route::prefix('admin')->group(function () {
        // ===== LAPORAN (ADMIN) =====
        Route::get('/laporan', [AdminController::class, 'getAllLaporan']);
        Route::get('/laporan/{laporan}', [AdminController::class, 'showLaporan']);
        Route::delete('/laporan/{laporan}', [AdminController::class, 'destroyLaporan']);
        Route::patch('/laporan/{laporan}/status', [AdminController::class, 'updateStatus']);

        // ===== PELAPOR DAN USER =====
        Route::get('/pelapor', [AdminController::class, 'listReporters']);
        Route::get('/pelapor/{user}/laporan', [AdminController::class, 'getLaporanByUser']);
        Route::get('/users/{user}', [AdminController::class, 'showUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword']);
    });
});