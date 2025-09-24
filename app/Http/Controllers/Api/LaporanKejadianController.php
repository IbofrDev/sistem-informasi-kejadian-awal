<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LaporanKejadian;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanKejadianController extends Controller
{
    /**
     * Menampilkan daftar riwayat laporan milik pengguna yang terautentikasi.
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Tambahkan with('user') agar daftar laporan juga memuat data user.
        // Ini akan berguna jika Anda membutuhkannya di halaman daftar.
        $laporan = $request->user()->laporanKejadian()->with('user')->latest()->get();
        return response()->json($laporan);
    }

    /**
     * Menyimpan laporan baru yang dikirim dari aplikasi mobile.
     */
    public function store(Request $request)
    {
        // Validasi data lengkap
        $validatedData = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'jabatan_pelapor' => 'required|string|max:255',
            'telepon_pelapor' => 'required|string|max:20',
            'jenis_kapal' => 'required|string|max:255',
            'nama_kapal' => 'required|string|max:255',
            'nama_kapal_kedua' => 'nullable|string|max:255',
            'bendera_kapal' => 'required|string|max:100',
            'grt_kapal' => 'required|integer',
            'imo_number' => 'nullable|string|max:100',
            'pelabuhan_asal' => 'required|string|max:255',
            'waktu_berangkat' => 'required|date',
            'pelabuhan_tujuan' => 'required|string|max:255',
            'estimasi_tiba' => 'required|date',
            'pemilik_kapal' => 'required|string|max:255',
            'kontak_pemilik' => 'required|string|max:20',
            'agen_lokal' => 'required|string|max:255',
            'kontak_agen' => 'required|string|max:20',
            'nama_pandu' => 'nullable|string|max:255',
            'nomor_register_pandu' => 'nullable|string|max:255',
            'jenis_muatan' => 'required|string',
            'jumlah_muatan' => 'required|string|max:100',
            'jumlah_penumpang' => 'required|integer',
            'posisi_lintang' => 'required|string|max:50',
            'posisi_bujur' => 'required|string|max:50',
            'tanggal_laporan' => 'required|date',
            'isi_laporan' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
        ]);

        $dataToStore = $validatedData;
        $dataToStore['user_id'] = $request->user()->id;

        $laporan = LaporanKejadian::create($dataToStore);

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('lampiran', 'public');
                $tipe = str_starts_with($file->getMimeType(), 'image') ? 'foto' : 'video';
                Lampiran::create([
                    'laporan_id' => $laporan->id,
                    'tipe_file' => $tipe,
                    'path_file' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Laporan berhasil dibuat',
            'data' => $laporan
        ], 201); // 201 Created
    }

    /**
     * Menampilkan detail satu laporan spesifik.
     */
    public function show(Request $request, LaporanKejadian $laporan)
    {
        // Otorisasi: pastikan user hanya bisa melihat laporannya sendiri
        if ($request->user()->id !== $laporan->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // --- PERBAIKAN UTAMA DI SINI ---
        // Muat relasi 'lampiran' DAN 'user'
        $laporan->load('lampiran', 'user');

        return response()->json($laporan);
    }

    /**
     * Menghapus sebuah laporan.
     */
    public function destroy(Request $request, LaporanKejadian $laporan)
    {
        // Otorisasi: pastikan user hanya bisa menghapus laporannya sendiri
        if ($request->user()->id !== $laporan->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $laporan->load('lampiran');
        foreach ($laporan->lampiran as $file) {
            Storage::disk('public')->delete($file->path_file);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus'], 200);
    }
}
