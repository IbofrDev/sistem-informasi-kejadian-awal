<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * ========================
     * 🔐 LOGIN
     * ========================
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Gunakan Auth::attempt agar patuh pada konfigurasi guard
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hapus token lama agar tidak numpuk
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    /**
     * ========================
     * 🧾 REGISTER
     * ========================
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'pt' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'nama' => $validatedData['nama'],
            'pt' => $validatedData['pt'],
            'jabatan' => $validatedData['jabatan'],
            'jenis_kapal' => $validatedData['jenis_kapal'],
            'phone_number' => $validatedData['phone_number'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'pelapor',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    /**
     * ========================
     * 👤 UPDATE PROFIL
     * ========================
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengganti password.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data profil
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->jenis_kapal = $request->jenis_kapal;

        // Ganti password bila diisi
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'errors' => ['current_password' => ['Password lama salah.']],
                ], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
        ], 200);
    }
}