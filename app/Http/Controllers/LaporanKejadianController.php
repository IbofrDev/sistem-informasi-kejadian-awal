<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use App\Models\LaporanKejadian;
use App\Models\Lampiran;    
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanKejadianController extends Controller
{
    /**
     * Menampilkan halaman dashboard pelapor dengan fitur filter bulan dan tahun.
     */
    public function index(Request $request)
    {
        // Ambil user yang login
        $user = auth()->user();

        // Ambil parameter filter dari URL: ?bulan=9&tahun=2025
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        // Ambil semua laporan user
        $laporanQuery = LaporanKejadian::where('user_id', $user->id);

        // Jika user memilih filter bulan dan tahun
        if ($bulan && $tahun) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan)
                         ->whereYear('tanggal_laporan', $tahun);
        } elseif ($bulan) {
            $laporanQuery->whereMonth('tanggal_laporan', $bulan);
        } elseif ($tahun) {
            $laporanQuery->whereYear('tanggal_laporan', $tahun);
        }

        // Urutkan berdasarkan tanggal_laporan terbaru
        $laporanKejadian = $laporanQuery->orderBy('tanggal_laporan', 'desc')->get();

        // Dapatkan daftar tahun unik dari database (untuk dropdown filter)
        $tahunList = LaporanKejadian::selectRaw('YEAR(tanggal_laporan) as tahun')
            ->whereNotNull('tanggal_laporan') // Pastikan tanggal tidak null
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Render ke view dashboard
        return view('dashboard', [
            'laporanKejadian' => $laporanKejadian,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahunList' => $tahunList
        ]);
    }

    /**
     * Menampilkan form untuk membuat laporan baru.
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * Menyimpan laporan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelapor'        => 'required|string|max:255',
            'jabatan_pelapor'     => 'required|string|max:255',
            'telepon_pelapor'     => 'required|string|max:20',
            'jenis_kapal'         => 'required|string|max:255',
            'nama_kapal'          => 'required|string|max:255',
            'nama_kapal_kedua'    => 'nullable|string|max:255',
            'bendera_kapal'       => 'required|string|max:100',
            'grt_kapal'           => 'required|integer',
            'imo_number'          => 'nullable|string|max:100',
            'pelabuhan_asal'      => 'required|string|max:255',
            'waktu_berangkat'     => 'required|date',
            'pelabuhan_tujuan'    => 'required|string|max:255',
            'estimasi_tiba'       => 'required|date',
            'pemilik_kapal'       => 'required|string|max:255',
            'kontak_pemilik'      => 'required|string|max:20',
            'agen_lokal'          => 'required|string|max:255',
            'kontak_agen'         => 'required|string|max:20',
            'nama_pandu'          => 'nullable|string|max:255',
            'nomor_register_pandu'=> 'nullable|string|max:255',
            'jenis_muatan'        => 'required|string',
            'jumlah_muatan'       => 'required|string|max:100',
            'jumlah_penumpang'    => 'required|integer',
            'posisi_lintang'      => 'required|string|max:50',
            'posisi_bujur'        => 'required|string|max:50',
            'tanggal_laporan'     => 'required|date',
            'isi_laporan'         => 'required|string',
            'lampiran.*'          => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,webm|max:20480', // 20MB
        ]);

        $dataToStore = $validatedData;
        $dataToStore['user_id'] = auth()->id();
        // Status default 'dikirim' sudah diatur di migrasi

        $laporan = LaporanKejadian::create($dataToStore);

        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('lampiran', 'public');
                $tipe = str_starts_with($file->getMimeType(), 'image') ? 'foto' : 'video';
                Lampiran::create([
                    'laporan_id' => $laporan->id,
                    'tipe_file'  => $tipe,
                    'path_file'  => $path,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Laporan kejadian berhasil dikirim!');
    }

    /**
     * Menampilkan detail laporan.
     */
    public function show(LaporanKejadian $laporan)
    {
        $this->authorize('view', $laporan);
        $laporan->load('lampiran'); // Eager load lampiran

        // 1. Ambil log aktivitas PELAPOR untuk modal
        $activities = Activity::forSubject($laporan)
                              ->where('causer_id', auth()->id()) // Filter hanya user yg login
                              ->latest()
                              ->get();

        // 2. Cari timestamp PERTAMA KALI status berubah jadi 'diverifikasi'
        $verificationLog = Activity::forSubject($laporan)
            ->where('event', 'updated')
            // Cari log dimana 'attributes' mengandung 'status_laporan' = 'diverifikasi'
            ->whereJsonContains('properties->attributes->status_laporan', 'diverifikasi')
            ->orderBy('created_at', 'asc') // Urutkan dari yg terlama
            ->first(); // Ambil yg pertama
        $verifiedAt = $verificationLog ? $verificationLog->created_at : null;

        // 3. Cari timestamp PERTAMA KALI status berubah jadi 'selesai'
        $completionLog = Activity::forSubject($laporan)
            ->where('event', 'updated')
            // Cari log dimana 'attributes' mengandung 'status_laporan' = 'selesai'
            ->whereJsonContains('properties->attributes->status_laporan', 'selesai')
            ->orderBy('created_at', 'asc') // Urutkan dari yg terlama
            ->first(); // Ambil yg pertama
        $completedAt = $completionLog ? $completionLog->created_at : null;

        // 4. Kirim semua data ke view
        return view('laporan.show', compact('laporan', 'activities', 'verifiedAt', 'completedAt'));
    }

    /**
     * Menampilkan form edit laporan.
     */
    public function edit(LaporanKejadian $laporan)
    {
        $this->authorize('update', $laporan);
        // Pastikan status 'dikirim' untuk bisa edit (jika perlu)
        // if ($laporan->status_laporan !== 'dikirim') {
        //     return redirect()->route('dashboard')->with('error', 'Laporan tidak bisa diedit lagi.');
        // }
        return view('laporan.edit', compact('laporan'));
    }

    /**
     * Update data laporan.
     */
    public function update(Request $request, LaporanKejadian $laporan)
    {
        $this->authorize('update', $laporan);
        // Pastikan status 'dikirim' untuk bisa update (jika perlu)
        // if ($laporan->status_laporan !== 'dikirim') {
        //     return redirect()->route('dashboard')->with('error', 'Laporan tidak bisa diupdate lagi.');
        // }

        // Validasi sama seperti store, kecuali lampiran
        $validatedData = $request->validate([
            'nama_pelapor'        => 'required|string|max:255',
            'jabatan_pelapor'     => 'required|string|max:255',
            'telepon_pelapor'     => 'required|string|max:20',
            'jenis_kapal'         => 'required|string|max:255',
            'nama_kapal'          => 'required|string|max:255',
            'nama_kapal_kedua'    => 'nullable|string|max:255',
            'bendera_kapal'       => 'required|string|max:100',
            'grt_kapal'           => 'required|integer',
            'imo_number'          => 'nullable|string|max:100',
            'pelabuhan_asal'      => 'required|string|max:255',
            'waktu_berangkat'     => 'required|date',
            'pelabuhan_tujuan'    => 'required|string|max:255',
            'estimasi_tiba'       => 'required|date',
            'pemilik_kapal'       => 'required|string|max:255',
            'kontak_pemilik'      => 'required|string|max:20',
            'agen_lokal'          => 'required|string|max:255',
            'kontak_agen'         => 'required|string|max:20',
            'nama_pandu'          => 'nullable|string|max:255',
            'nomor_register_pandu'=> 'nullable|string|max:255',
            'jenis_muatan'        => 'required|string',
            'jumlah_muatan'       => 'required|string|max:100',
            'jumlah_penumpang'    => 'required|integer',
            'posisi_lintang'      => 'required|string|max:50',
            'posisi_bujur'        => 'required|string|max:50',
            'tanggal_laporan'     => 'required|date',
            'isi_laporan'         => 'required|string',
            // Validasi lampiran tidak di sini jika mau pakai logic hapus/tambah terpisah
        ]);

        $laporan->update($validatedData);

        // Logic untuk update lampiran bisa ditambahkan di sini jika perlu

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * Hapus laporan.
     */
    public function destroy(LaporanKejadian $laporan)
    {
        $this->authorize('delete', $laporan);
        
        // Hapus file lampiran dari storage sebelum hapus record DB
        $laporan->load('lampiran');
        foreach ($laporan->lampiran as $file) {
             \Illuminate\Support\Facades\Storage::disk('public')->delete($file->path_file);
        }
        // Hapus record lampiran dan laporan (cascade delete di DB akan menghapus lampiran juga)
        $laporan->delete(); 
        
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Cetak laporan ke PDF.
     */
    public function print(LaporanKejadian $laporan)
    {
        try {
            $this->authorize('view', $laporan);
            $laporan->load('lampiran');

            \Log::info('Mulai buat PDF untuk Pelapor', ['laporan_id' => $laporan->id]);

            // Menggunakan view yang sama dengan admin? Pastikan pathnya benar
            $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]); 

            \Log::info('View PDF berhasil dibaca untuk Pelapor');

            // tampilkan di tab baru
            return $pdf->stream('laporan-kejadian-' . $laporan->id . '.pdf');

        } catch (\Throwable $e) {
            \Log::error('Gagal buat PDF untuk Pelapor: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return response()->make(
                '<h2>Terjadi kesalahan saat membuat PDF:</h2><pre>'
                . e($e->getMessage()) . '</pre><p>Silakan cek log aplikasi untuk detail.</p>',
                500,
                ['Content-Type' => 'text/html']
            );
        }
    }
}