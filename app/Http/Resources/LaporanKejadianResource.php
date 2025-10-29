<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LaporanKejadianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            // 🕒 Status & waktu laporan
            'status_laporan' => $this->status_laporan,
            'sent_at' => $this->sent_at,
            'verified_at' => $this->verified_at,
            'completed_at' => $this->completed_at,

            // 🧍‍♂️ Data pelapor
            'nama_pelapor' => $this->nama_pelapor,
            'jabatan_pelapor' => $this->jabatan_pelapor,
            'telepon_pelapor' => $this->telepon_pelapor,

            // 🚢 Data kapal
            'nama_kapal' => $this->nama_kapal,
            'jenis_kapal' => $this->jenis_kapal,
            'nama_kapal_kedua' => $this->nama_kapal_kedua,
            'bendera_kapal' => $this->bendera_kapal,
            'grt_kapal' => $this->grt_kapal,
            'imo_number' => $this->imo_number,

            // ⚓ Perjalanan
            'pelabuhan_asal' => $this->pelabuhan_asal,
            'pelabuhan_tujuan' => $this->pelabuhan_tujuan,
            'waktu_berangkat' => $this->waktu_berangkat,
            'estimasi_tiba' => $this->estimasi_tiba,

            // 🤝 Pemilik & agen
            'pemilik_kapal' => $this->pemilik_kapal,
            'kontak_pemilik' => $this->kontak_pemilik,
            'agen_lokal' => $this->agen_lokal,
            'kontak_agen' => $this->kontak_agen,

            // 🧭 Pandu dan Muatan (🔥 tambahan penting)
            'nama_pandu' => $this->nama_pandu,
            'nomor_register_pandu' => $this->nomor_register_pandu,
            'jenis_muatan' => $this->jenis_muatan,
            'jumlah_muatan' => $this->jumlah_muatan,
            'jumlah_penumpang' => $this->jumlah_penumpang,

            // 📍 Lokasi
            'posisi_lintang' => $this->posisi_lintang,
            'posisi_bujur' => $this->posisi_bujur,
            'tanggal_laporan' => $this->tanggal_laporan,

            // 📝 Isi laporan
            'isi_laporan' => $this->isi_laporan,

            // ⚠️ Jenis kecelakaan
            'jenis_kecelakaan' => $this->jenis_kecelakaan,
            'pihak_terkait' => $this->pihak_terkait,

            // 📎 Lampiran & user
            'lampiran' => $this->lampiran,
            'user' => $this->user,
        ];
    }
}