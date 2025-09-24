<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan semua laporan dan statistik dinamis.
     */
    public function index(Request $request)
    {
        // Ambil status yang dipilih dari URL, contoh: ?status=diverifikasi
        $selectedStatus = $request->query('status');

        // ======== LOGIKA UNTUK TABEL LAPORAN ========
        // Query dasar untuk mengambil data laporan
        $laporanQuery = LaporanKejadian::query()->with('user');

        // Jika ada status yang dipilih, filter query-nya
        if ($selectedStatus) {
            $laporanQuery->where('status_laporan', $selectedStatus);
        }

        // Ambil data untuk tabel dengan paginasi, urutkan dari yang terbaru
        // withQueryString() berfungsi agar filter status tidak hilang saat berpindah halaman
        $semuaLaporan = $laporanQuery->latest('tanggal_laporan')
            ->paginate(10)
            ->withQueryString();

        // ======== LOGIKA BARU UNTUK KARTU STATISTIK DINAMIS ========
        if (!$selectedStatus) {
            // JIKA TIDAK ADA FILTER (Tampilan "Semua")
            // Hitung semua statistik secara global
            $totalLaporan = LaporanKejadian::count();
            $laporanBaru = LaporanKejadian::where('status_laporan', 'dikirim')->count();
            $perluVerifikasi = LaporanKejadian::where('status_laporan', 'diverifikasi')->count();
            $laporanSelesai = LaporanKejadian::where('status_laporan', 'selesai')->count();
        } else {
            // JIKA ADA FILTER STATUS YANG AKTIF
            // Atur semua hitungan ke 0 terlebih dahulu
            $laporanBaru = 0;
            $perluVerifikasi = 0;
            $laporanSelesai = 0;

            // Hitung jumlah laporan hanya untuk status yang sedang difilter
            $filteredCount = LaporanKejadian::where('status_laporan', $selectedStatus)->count();

            // Atur kartu "Total Laporan" agar menampilkan jumlah yang difilter
            $totalLaporan = $filteredCount;

            // Perbarui nilai untuk kartu yang sesuai dengan filter yang aktif
            switch ($selectedStatus) {
                case 'dikirim':
                    $laporanBaru = $filteredCount;
                    break;
                case 'diverifikasi':
                    $perluVerifikasi = $filteredCount;
                    break;
                case 'selesai':
                    $laporanSelesai = $filteredCount;
                    break;
            }
        }

        // Kirim semua variabel yang sudah dihitung ke view
        return view('admin.dashboard', [
            'totalLaporan' => $totalLaporan,
            'laporanBaru' => $laporanBaru,
            'perluVerifikasi' => $perluVerifikasi,
            'laporanSelesai' => $laporanSelesai,
            'semuaLaporan' => $semuaLaporan,
            'selectedStatus' => $selectedStatus,
        ]);
    }


    /**
     * Menampilkan detail dari satu laporan spesifik untuk admin.
     */
    public function show(LaporanKejadian $laporan)
    {
        // Eager load relasi lampiran dan user (pelapor)
        $laporan->load('lampiran', 'user');

        // Ambil data user dari relasi
        $pelapor = $laporan->user;

        // Kirim kedua variabel ('laporan' dan 'pelapor') ke view
        return view('admin.detail', [
            'laporan' => $laporan,
            'pelapor' => $pelapor
        ]);
    }

    /**
     * Memperbarui status laporan.
     */
    public function updateStatus(Request $request, LaporanKejadian $laporan)
    {
        $request->validate(['status' => 'required|string|in:dikirim,diverifikasi,selesai']);
        $laporan->status_laporan = $request->input('status');
        $laporan->save();

        // Redirect kembali ke halaman sebelumnya untuk menjaga filter tetap aktif
        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Menghapus laporan dari database (Admin).
     */
    public function destroy(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran');
        foreach ($laporan->lampiran as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->path_file);
        }
        $laporan->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Laporan dan semua file lampirannya berhasil dihapus.');
    }

    /**
     * Menampilkan daftar semua pengguna dengan peran 'pelapor'.
     */
    public function listReporters()
    {
        $pelapor = User::where('role', 'pelapor')
            ->withCount('laporanKejadian')
            ->with('latestReport')
            ->get();

        return view('admin.pelapor-list', ['pelapor' => $pelapor]);
    }

    /**
     * Menampilkan detail semua laporan dari satu pelapor.
     */
    public function showReporterDetails(User $user)
    {
        $laporanKejadian = $user->laporanKejadian()
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        return view('admin.pelapor-detail', [
            'pelapor' => $user,
            'laporanKejadian' => $laporanKejadian
        ]);
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF(LaporanKejadian $laporan)
    {
        // Muat data ke dalam view PDF
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);

        // Tampilkan PDF di browser
        return $pdf->stream('laporan-kejadian-' . $laporan->id . '.pdf');
    }
}
