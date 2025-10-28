<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaporanKejadianResource;
use App\Models\LaporanKejadian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * 🧾 Mengambil semua laporan untuk admin, lengkap dengan data pelapor.
     * Flutter memanggil endpoint: GET /api/admin/laporan
     */
    public function getAllLaporan(Request $request)
    {
        // Gunakan eager loading untuk performa
        $query = LaporanKejadian::with(['user', 'lampiran'])->latest();

        if ($request->has('status')) {
            $query->where('status_laporan', $request->status);
        }

        $laporan = $query->get();

        return response()->json($laporan, 200);
    }

    /**
     * 👥 Menampilkan daftar semua pengguna dengan role 'pelapor'.
     */
    public function listReporters()
    {
        $pelapor = User::where('role', 'pelapor')
            ->withCount('laporanKejadian')
            ->with('latestReport')
            ->orderBy('nama')
            ->get();

        return response()->json($pelapor, 200);
    }

    /**
     * 📄 Menampilkan detail laporan spesifik untuk Admin
     * (dengan relasi user dan lampiran).
     * Menggunakan resource agar seluruh field dikirim utuh.
     * Flutter memanggil endpoint: GET /api/admin/laporan/{id}
     */
    public function showLaporan(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran', 'user');
        $laporan->refresh(); // pastikan data terbaru masuk termasuk kolom waktu

        // ✅ kirim seluruh field laporan dengan resource resmi
        return new LaporanKejadianResource($laporan);
    }

    /**
     * ✏️ Memperbarui status laporan oleh admin.
     * Sekaligus memberi tanda waktu kapan laporan diverifikasi / selesai.
     */
    public function updateStatus(Request $request, LaporanKejadian $laporan)
    {
        // 🧾 Validasi input status
        $validatedData = $request->validate([
            'status_laporan' => 'required|string|in:dikirim,diverifikasi,selesai',
        ]);

        // 📝 Set status laporan baru
        $laporan->status_laporan = $validatedData['status_laporan'];

        // 🕒 Atur waktu otomatis sesuai status
        switch ($laporan->status_laporan) {
            case 'dikirim':
                // Isi waktu kirim hanya jika belum ada
                if (empty($laporan->sent_at)) {
                    $laporan->sent_at = now();
                }
                break;

            case 'diverifikasi':
                // Set waktu diverifikasi saat status berubah ke diverifikasi
                $laporan->verified_at = now();
                break;

            case 'selesai':
                // Set waktu selesai saat status berubah ke selesai
                $laporan->completed_at = now();
                break;
        }

        // 💾 Simpan hanya sekali agar semua kolom tersimpan bersamaan
        $laporan->save();

        // 🔄 Kirim data terbaru ke Flutter
        return response()->json([
            'message' => 'Status laporan berhasil diperbarui.',
            'data' => new LaporanKejadianResource($laporan->fresh()),
        ], 200);
    }

    /**
     * 🗑️ Menghapus laporan (beserta lampiran) oleh admin.
     */
    public function destroyLaporan(LaporanKejadian $laporan)
    {
        $laporan->load('lampiran');

        foreach ($laporan->lampiran as $file) {
            if ($file->path_file) {
                Storage::disk('public')->delete($file->path_file);
            }
        }

        $laporan->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.'
        ], 200);
    }

    /**
     * 📋 Mengambil semua laporan dari satu pelapor spesifik.
     * Flutter memanggil: /api/admin/pelapor/{user}/laporan
     */
    public function getLaporanByUser(User $user)
    {
        $laporan = $user->laporanKejadian()
            ->with('lampiran')
            ->latest()
            ->get();

        return response()->json($laporan, 200);
    }

    /**
     * 👤 Menampilkan detail pengguna untuk Admin.
     */
    public function showUser(User $user)
    {
        $user->loadCount('laporanKejadian');
        return response()->json($user, 200);
    }

    /**
     * 🛠️ Memperbarui data pengguna oleh Admin.
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
            'data' => $user->fresh()
        ], 200);
    }

    /**
     * 🔑 Reset password pengguna oleh Admin.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Pastikan hanya admin yang dapat melakukan reset
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $validatedData = $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'message' => 'Password untuk ' . $user->nama . ' berhasil diperbarui.',
        ], 200);
    }
}