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
    ];

    /**
     * Mendefinisikan relasi "hasMany" ke model Lampiran.
     */
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'laporan_id');
    }

    /**
     * PERBAIKAN: Tambahkan relasi "belongsTo" ke model User.
     * Ini penting agar Laravel bisa mengambil data pelapor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // 3. TAMBAHKAN FUNGSI INI UNTUK MENGATUR LOGGER
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Mencatat semua kolom di $fillable
            ->logFillable() 
            
            // Hanya mencatat perubahan jika ada kolom yang benar-benar berubah
            ->logOnlyDirty() 
            
            // Tidak menyimpan log jika hanya kolom 'updated_at' yang berubah
            ->dontSubmitEmptyLogs() 
            
            // Memberi nama log agar mudah dibaca
            ->useLogName('Laporan Kejadian') 
            
            // Memberi deskripsi untuk setiap aksi
            ->setDescriptionForEvent(fn(string $eventName) => "Laporan kejadian telah {$eventName}");
    }
}