<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LaporanKejadian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // <-- PERUBAHAN: Ditambahkan untuk hashing password
use Illuminate\Support\Str;      // <-- PERUBAHAN: Ditambahkan untuk generate string acak

class AdminController extends Controller
{
    /**
     * Mengambil semua laporan untuk admin, lengkap dengan data pelapor.
     */
    public function getAllLaporan(Request $request)
    {
        // PERBAIKAN 1: Gunakan with('user') untuk Eager Loading
        // Ini lebih efisien untuk mengambil koleksi data beserta relasinya.
        $query = LaporanKejadian::with('user')->latest();

        if ($request->has('status')) {
            $query->where('status_laporan', $request->status);
        }

        $laporan = $query->get();
        return response()->json($laporan);
    }

    /**
     * Menampilkan daftar semua pengguna dengan role 'pelapor'.
     */
    public function listReporters()
    {
        $pelapor = User::where('role', 'pelapor')
            ->withCount('laporanKejadian')
            ->with('latestReport')
            ->get();
        return response()->json($pelapor);
    }

    /**
     * Menampilkan detail laporan spesifik untuk Admin, lengkap dengan data pelapor.
     */
    public function showLaporan(LaporanKejadian $laporan)
    {
        // PERBAIKAN 2: Tambahkan 'user' ke dalam method load()
        // Ini akan memuat relasi user dan lampiran ke dalam objek laporan
        // sebelum dikirim sebagai JSON.
        $laporan->load('lampiran', 'user');

        return response()->json($laporan);
    }

    /**
     * Memperbarui status sebuah laporan.
     */
    public function updateStatus(Request $request, LaporanKejadian $laporan)
    {
        $validatedData = $request->validate([
            'status_laporan' => 'required|string|in:dikirim,diverifikasi,selesai',
        ]);

        $laporan->update($validatedData);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui',
            'data' => $laporan,
        ]);
    }

    /**
     * Menghapus laporan (Admin).
     */
    public function destroyLaporan(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran');
        foreach ($laporan->lampiran as $file) {
            Storage::disk('public')->delete($file->path_file);
        }
        $laporan->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }

    /**
     * Mengambil semua laporan dari satu pelapor spesifik.
     */
    public function getLaporanByUser(User $user)
    {
        $laporan = $user->laporanKejadian()->latest()->get();
        return response()->json($laporan);
    }

    /**
     * Menampilkan detail satu pengguna spesifik.
     */
    public function showUser(User $user)
    {
        return response()->json($user);
    }

    /**
     * Memperbarui data pengguna oleh Admin.
     */
    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $user->update($validatedData);

        return response()->json([
            'message' => 'Data pengguna berhasil diperbarui.',
            'data' => $user
        ]);
    }

    /**
     * Mengatur ulang password pengguna oleh Admin.
     */
    // --- PERUBAHAN UTAMA DI FUNGSI INI ---
    public function resetPassword(Request $request, User $user)
    {
        // 1. Otorisasi: Pastikan pengguna yang sedang login adalah admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        // 2. Validasi: Pastikan password baru dikirim dan memenuhi syarat
        $validatedData = $request->validate([
            'password' => 'required|string|min:8', // Wajib ada, minimal 8 karakter
        ]);

        // 3. Update password user dengan password baru yang sudah di-hash
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // 4. Kembalikan pesan sukses
        return response()->json([
            'message' => 'Password untuk ' . $user->nama . ' berhasil diperbarui.',
        ]);
    }
}

