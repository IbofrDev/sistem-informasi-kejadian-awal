<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Penting untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'pt',               // 🆕 Tambahkan kolom PT / Perusahaan
        'jabatan',
        'jenis_kapal',
        'phone_number',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan dalam serialisasi (mis. JSON response).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi atribut ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: satu User memiliki banyak LaporanKejadian.
     */
    public function laporanKejadian(): HasMany
    {
        return $this->hasMany(LaporanKejadian::class);
    }

    /**
     * Relasi: satu User memiliki satu laporan kejadian terbaru.
     */
    public function latestReport()
    {
        return $this->hasOne(LaporanKejadian::class)->latestOfMany();
    }
}