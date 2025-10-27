<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // 1. Tambahkan ini
use Illuminate\Support\Facades\Auth; // 2. Tambahkan ini
use App\Models\LaporanKejadian;    // 3. Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 4. TAMBAHKAN SEMUA KODE INI
        // Kita gunakan View Composer untuk membagikan data
        // HANYA ke layout 'layouts.app' dan HANYA jika user adalah admin.
        View::composer('layouts.app', function ($view) {
            
            // Cek dulu apakah user login dan rolenya admin
            if (Auth::check() && Auth::user()->role == 'admin') {
                
                // Ambil jumlah total laporan 'dikirim' untuk badge
                $laporanBaruCount = LaporanKejadian::where('status_laporan', 'dikirim')
                                                    ->count();
                
                // Ambil 5 laporan 'dikirim' terbaru untuk dropdown
                $laporanBaru = LaporanKejadian::where('status_laporan', 'dikirim')
                                            ->with('user') // Eager load data pelapor
                                            ->latest('created_at')
                                            ->take(5)
                                            ->get();

            } else {
                // Jika bukan admin (atau belum login), set data jadi kosong
                $laporanBaruCount = 0;
                $laporanBaru = collect(); // Buat koleksi kosong
            }

            // Kirim kedua variabel ini ke view 'layouts.app'
            $view->with('laporanBaruCount', $laporanBaruCount)
                 ->with('laporanBaru', $laporanBaru);
        });
    }
}
