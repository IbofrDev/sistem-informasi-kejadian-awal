<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna dengan fungsionalitas pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Diubah agar menampilkan semua role, bukan hanya pelapor
        $query = User::withCount('laporanKejadian');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(9)->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Menampilkan detail spesifik dari seorang user/pelapor.
     */
    public function show(User $user)
    {
        $laporanKejadian = $user->laporanKejadian()->latest()->get();

        return view('admin.pelapor-detail', [
            'pelapor' => $user,
            'laporanKejadian' => $laporanKejadian
        ]);
    }

    /**
     * Menampilkan form untuk mengedit data user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Memperbarui data user di database, termasuk password dan role.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'jabatan' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            // Tambahkan validasi untuk role
            'role' => ['required', Rule::in(['admin', 'pelapor'])],
        ]);

        // Jika admin mengisi password baru, hash dulu
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // jangan timpa password lama jika kosong
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index') // Redirect ke daftar user
            ->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Menghapus user dari database.
     * BARU: Menambahkan fungsi destroy
     */
    public function destroy(User $user)
    {
        // Tambahkan proteksi agar admin tidak bisa menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
