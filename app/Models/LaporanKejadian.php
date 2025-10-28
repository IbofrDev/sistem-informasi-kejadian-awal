<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 1. IMPORT CLASS YANG DIPERLUKAN
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LaporanKejadian extends Model
{
    // 2. GUNAKAN TRAIT LOGGER
    use HasFactory, LogsActivity;

    protected $table = 'laporan_kejadian';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'status_laporan',

        // 🕒 Kolom waktu status laporan
        'sent_at',
        'verified_at',
        'completed_at',

        // 🔹 Data Pelapor
        'nama_pelapor',
        'jabatan_pelapor',
        'telepon_pelapor',

        // 🔹 Data Kapal
        'jenis_kapal',
        'nama_kapal',
        'nama_kapal_kedua',
        'bendera_kapal',
        'grt_kapal',
        'imo_number',

        // 🔹 Perjalanan
        'pelabuhan_asal',
        'waktu_berangkat',
        'pelabuhan_tujuan',
        'estimasi_tiba',

        // 🔹 Pemilik & Agen
        'pemilik_kapal',
        'kontak_pemilik',
        'agen_lokal',
        'kontak_agen',

        // 🔹 Pilot & Muatan
        'nama_pandu',
        'nomor_register_pandu',
        'jenis_muatan',
        'jumlah_muatan',
        'jumlah_penumpang',

        // 🔹 Lokasi & Isi
        'posisi_lintang',
        'posisi_bujur',
        'tanggal_laporan',
        'isi_laporan',

        // 🆕 Tambahan agar sesuai dengan input dari Flutter
        'jenis_kecelakaan',
        'pihak_terkait',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'verified_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    /**
     * Relasi: satu laporan memiliki banyak lampiran.
     */
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'laporan_id');
    }

    /**
     * Relasi: laporan dimiliki oleh satu user (pelapor).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Konfigurasi aktivitas log.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()               // mencatat semua kolom fillable
            ->logOnlyDirty()              // hanya mencatat perubahan nyata
            ->dontSubmitEmptyLogs()       // tidak log jika hanya updated_at
            ->useLogName('Laporan Kejadian')
            ->setDescriptionForEvent(fn(string $eventName) => "Laporan kejadian telah {$eventName}");
    }
}