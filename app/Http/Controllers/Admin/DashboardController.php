<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedStatus = $request->query('status');
        $search = $request->query('search');

        $laporanQuery = LaporanKejadian::query()->with('user');

        // Filter status
        if ($selectedStatus) {
            $laporanQuery->where('status_laporan', $selectedStatus);
        }

        // Filter pencarian
        if ($search) {
            $laporanQuery->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%$search%")
                      ->orWhere('nama_kapal', 'LIKE', "%$search%")
                      ->orWhere('jenis_kapal', 'LIKE', "%$search%")
                      ->orWhereDate('tanggal_laporan', $search)
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('nama', 'LIKE', "%$search%");
                      });
            });
        }

        $semuaLaporan = $laporanQuery->latest('tanggal_laporan')
            ->paginate(10)
            ->withQueryString();

        // Statistik
        if (!$selectedStatus) {
            $totalLaporan = LaporanKejadian::count();
            $laporanBaru = LaporanKejadian::where('status_laporan', 'dikirim')->count();
            $perluVerifikasi = LaporanKejadian::where('status_laporan', 'diverifikasi')->count();
            $laporanSelesai = LaporanKejadian::where('status_laporan', 'selesai')->count();
        } else {
            $laporanBaru = 0;
            $perluVerifikasi = 0;
            $laporanSelesai = 0;

            $filteredCount = LaporanKejadian::where('status_laporan', $selectedStatus)->count();
            $totalLaporan = $filteredCount;

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

        return view('admin.dashboard', [
            'totalLaporan' => $totalLaporan,
            'laporanBaru' => $laporanBaru,
            'perluVerifikasi' => $perluVerifikasi,
            'laporanSelesai' => $laporanSelesai,
            'semuaLaporan' => $semuaLaporan,
            'selectedStatus' => $selectedStatus,
            'search' => $search
        ]);
    }

    public function show(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran', 'user');
        $pelapor = $laporan->user;

        return view('admin.detail', compact('laporan', 'pelapor'));
    }

    public function updateStatus(Request $request, LaporanKejadian $laporan)
    {
        $request->validate(['status' => 'required|string|in:dikirim,diverifikasi,selesai']);
        $laporan->status_laporan = $request->status;
        $laporan->save();

        $status = $laporan->status_laporan;
        $message = 'Status laporan berhasil diperbarui.';
        $icon = 'success';

        if ($status === 'diverifikasi') {
            $message = 'Laporan berhasil diverifikasi.';
            $icon = 'warning';
        } elseif ($status === 'selesai') {
            $message = 'Laporan telah selesai diproses.';
            $icon = 'success';
        } elseif ($status === 'dikirim') {
            $message = 'Laporan telah dikirim.';
            $icon = 'info';
        }

        return back()->with(['success' => $message, 'swal_icon' => $icon]);
    }

    public function destroy(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran');
        foreach ($laporan->lampiran as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->path_file);
        }
        $laporan->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Laporan dan semua file lampirannya berhasil dihapus.');
    }

    public function listReporters()
    {
        $pelapor = User::where('role', 'pelapor')
            ->withCount('laporanKejadian')
            ->with('latestReport')
            ->get();

        return view('admin.pelapor-list', ['pelapor' => $pelapor]);
    }

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

    public function printPDF(LaporanKejadian $laporan)
    {
        $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);
        return $pdf->stream('laporan-kejadian-' . $laporan->id . '.pdf');
    }
}