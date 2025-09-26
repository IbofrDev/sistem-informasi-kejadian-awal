<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanKejadianController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// Rute Dashboard yang "Pintar"
Route::get('/dashboard', function () {
    // Jika yang login adalah admin, langsung arahkan ke dashboard admin
    if (auth()->user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Jika bukan admin (pelapor), tampilkan riwayat laporannya
    $user = auth()->user();
    $laporanKejadian = $user->laporanKejadian()->latest()->get();
    return view('dashboard', [
        'laporanKejadian' => $laporanKejadian
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup Rute untuk Pengguna yang Sudah Login (umum)
Route::middleware('auth')->group(function () {
    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute CRUD Laporan untuk Pelapor
    Route::resource('laporan', LaporanKejadianController::class);
    Route::get('/laporan/{laporan}/print', [LaporanKejadianController::class, 'print'])
    ->middleware('auth')
    ->name('laporan.print');
});

// Grup Rute KHUSUS untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan/{laporan}', [AdminDashboardController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporan}/status', [AdminDashboardController::class, 'updateStatus'])->name('laporan.updateStatus');
    Route::delete('/laporan/{laporan}', [AdminDashboardController::class, 'destroy'])->name('laporan.destroy');
    Route::get('/laporan/{laporan}/print', [AdminDashboardController::class, 'printPDF'])->name('laporan.print');

    // Rute untuk mengelola user
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    // --- RUTE BARU UNTUK HAPUS USER ---
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// Mengembalikan rute 'login' dan mengalihkannya ke halaman utama
Route::get('/login', function () {
    return redirect('/');
})->name('login');

// Memanggil semua rute autentikasi (HANYA SATU KALI di paling akhir)
require __DIR__ . '/auth.php';
