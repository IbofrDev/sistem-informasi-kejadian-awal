<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Penting untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Pastikan HasApiTokens ada di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'phone_number',
        'email',
        'password',
        'role',
        'jabatan',
        'jenis_kapal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Mendefinisikan relasi bahwa satu User bisa memiliki banyak LaporanKejadian.
     */
    public function laporanKejadian(): HasMany
    {
        return $this->hasMany(LaporanKejadian::class);
    }

    /**
     * Mendapatkan satu laporan kejadian terakhir dari user.
     */
    public function latestReport()
    {
        return $this->hasOne(LaporanKejadian::class)->latestOfMany();
    }
}
