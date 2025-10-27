<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan daftar laporan pelapor dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        // Jika yang login adalah admin, langsung arahkan ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Ambil user yang sedang login
        $user = auth()->user();

        // Query dasar laporan milik user
        $laporanQuery = $user->laporanKejadian()->latest();

        /**
         * 🔎 Filter & pencarian dinamis
         */
        // Filter kata kunci umum (?q=)
        if ($search = $request->input('q')) {
            $laporanQuery->where(function ($q) use ($search) {
                $q->where('nama_kapal', 'like', "%{$search}%")
                  ->orWhere('isi_laporan', 'like', "%{$search}%")
                  ->orWhere('pelabuhan_asal', 'like', "%{$search}%")
                  ->orWhere('pelabuhan_tujuan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal mulai (?from_date=)
        if ($from = $request->input('from_date')) {
            $laporanQuery->whereDate('tanggal_laporan', '>=', $from);
        }

        // Filter tanggal akhir (?to_date=)
        if ($to = $request->input('to_date')) {
            $laporanQuery->whereDate('tanggal_laporan', '<=', $to);
        }

        // Filter status laporan (?status_laporan=)
        if ($status = $request->input('status_laporan')) {
            $laporanQuery->where('status_laporan', $status);
        }

        /**
         * 🔹 Tambahan: filter berdasarkan bulan dan tahun (?bulan= & ?tahun=)
         */
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        if ($bulan && $tahun) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan)
                         ->whereYear('tanggal_laporan', $tahun);
        } elseif ($bulan) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan);
        } elseif ($tahun) {
            $laporanQuery->whereYear('tanggal_laporan', $tahun);
        }

        /**
         * 🔹 Dropdown daftar tahun unik untuk filter
         */
        $tahunList = LaporanKejadian::selectRaw('YEAR(tanggal_laporan) as tahun')
            ->where('user_id', $user->id)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Ambil hasil akhir dengan pagination
        $laporanKejadian = $laporanQuery->paginate(10)->withQueryString();

        // Kembalikan tampilan dashboard
        return view('dashboard', [
            'laporanKejadian' => $laporanKejadian,
            'tahunList'       => $tahunList, // ✅ dikirim ke view agar tidak error
        ]);
    }
    public function printPDF(LaporanKejadian $laporan)
{
    // pastikan relasi lampiran ikut dimuat
    $laporan->load('lampiran');

    // buat PDF dari template yang sudah ada
    $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);

    // tampilkan di tab baru
    return $pdf->stream('laporan-kejadian-' . $laporan->id . '.pdf');
}
}