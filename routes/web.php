<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanKejadianController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| (TIDAK ADA PERUBAHAN. File ini sudah benar untuk solusi Modal Pop-up)
|
*/

// 🔹 Rute Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// 🔹 Rute Dashboard Pelapor (dengan filter dan pencarian)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 🔹 Rute untuk Pengguna Login Umum
Route::middleware('auth')->group(function () {
    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Laporan untuk Pelapor
    Route::resource('laporan', LaporanKejadianController::class);

    // 🔹 Cetak laporan ke PDF
    Route::get('/laporan/{laporan}/print', [LaporanKejadianController::class, 'print'])
        ->middleware('auth')
        ->name('laporan.print');
});

// 🔹 Grup Rute KHUSUS untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Aksi laporan admin
    Route::get('/laporan/{laporan}', [AdminDashboardController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporan}/status', [AdminDashboardController::class, 'updateStatus'])->name('laporan.updateStatus');
    Route::delete('/laporan/{laporan}', [AdminDashboardController::class, 'destroy'])->name('laporan.destroy');

    // Cetak PDF versi admin
    Route::get('/laporan/{laporan}/print', [AdminDashboardController::class, 'printPDF'])->name('laporan.print');

    // Manajemen pengguna
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// 🔹 Rute uji DomPDF — berguna untuk memastikan pustaka PDF berfungsi
Route::get('/tes-pdf', function () {
    $pdf = Pdf::loadHTML('<h1>Hello KSOP</h1><p>DomPDF sudah berfungsi dengan baik!</p>');
    return $pdf->stream('tes.pdf');
});

// 🔹 Alihkan rute login ke halaman utama bagi user yang belum autentikasi
Route::get('/login', function () {
    return redirect('/');
})->name('login');

// 🔹 Tambahkan rute autentikasi bawaan Laravel (register, forgot password, dll)
require __DIR__ . '/auth.php';
