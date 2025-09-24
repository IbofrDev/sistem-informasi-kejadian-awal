<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LaporanKejadian;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Hapus data lama jika ada (opsional, tapi baik untuk testing)
        User::query()->delete();
        LaporanKejadian::query()->delete();

        // 2. Buat satu Admin utama untuk Anda
        User::factory()->create([
            'nama' => 'Admin User',
            'email' => 'admin@contoh.com',
            'password' => Hash::make('password'), // passwordnya: password
            'role' => 'admin',
            'jabatan' => 'Administrator',
            'jenis_kapal' => 'N/A',
        ]);

        // 3. Buat 10 pengguna "pelapor" palsu
        User::factory(10)->create([
            'role' => 'pelapor',
            'password' => Hash::make('password'),
        ]);

        // 4. Buat 50 laporan palsu dan acak pemiliknya dari 10 pelapor yang ada
        LaporanKejadian::factory(50)->create([
            // Pilih user_id secara acak dari user yang berperan sebagai 'pelapor'
            'user_id' => User::where('role', 'pelapor')->inRandomOrder()->first()->id,
        ]);
    }
}
