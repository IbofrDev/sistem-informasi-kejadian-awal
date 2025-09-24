<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKejadian extends Model
{
    use HasFactory;

    protected $table = 'laporan_kejadian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
}
