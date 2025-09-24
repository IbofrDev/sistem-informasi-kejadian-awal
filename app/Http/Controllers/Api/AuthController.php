<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Ditambahkan untuk validasi
use Illuminate\Validation\Rule; // Ditambahkan untuk validasi unique

class AuthController extends Controller
{
    /**
     * Handle a login request to the API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba untuk mengautentikasi pengguna
        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 3. Buat token API baru untuk pengguna
            $token = $user->createToken('auth_token')->plainTextToken;

            // 4. Kembalikan data pengguna dan token sebagai respons
            return response()->json([
                'message' => 'Login berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }

        // 5. Jika gagal, kembalikan pesan error
        return response()->json([
            'message' => 'Email atau password salah.'
        ], 401); // 401 Unauthorized
    }

    /**
     * Handle a registration request to the API.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'nama' => $validatedData['nama'],
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
            'user' => $user
        ]);
    }

    /**
     * Handle a profile update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        // 1. Ambil data pengguna yang sedang login
        $user = $request->user();

        // 2. Validasi input dari form edit profil
        // Perhatikan `Rule::unique('users')->ignore($user->id)`
        // Ini memastikan validasi unik tidak gagal karena data milik pengguna itu sendiri.
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jenis_kapal' => 'required|string|max:255',
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // 3. Update data pengguna dengan data yang sudah divalidasi
        $user->update($validator->validated());

        // 4. Kembalikan respons sukses dengan data pengguna yang baru
        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ], 200); // 200 OK
    }
}
