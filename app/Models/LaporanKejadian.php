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
        'nama_pelapor',
        'jabatan_pelapor',
        'telepon_pelapor',
        'jenis_kapal',
        'nama_kapal',
        'nama_kapal_kedua',
        'bendera_kapal',
        'grt_kapal',
        'imo_number',
        'pelabuhan_asal',
        'waktu_berangkat',
        'pelabuhan_tujuan',
        'estimasi_tiba',
        'pemilik_kapal',
        'kontak_pemilik',
        'agen_lokal',
        'kontak_agen',
        'nama_pandu',
        'nomor_register_pandu',
        'jenis_muatan',
        'jumlah_muatan',
        'jumlah_penumpang',
        'posisi_lintang',
        'posisi_bujur',
        'tanggal_laporan',
        'isi_laporan',

        // 🆕 Tambahan agar sesuai dengan input dari Flutter
        'jenis_kecelakaan',
        'pihak_terkait',
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