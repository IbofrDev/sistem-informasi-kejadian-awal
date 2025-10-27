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

            // 🧍‍♂️ Data Pelapor
            'nama_pelapor' => $this->nama_pelapor,
            'jabatan_pelapor' => $this->jabatan_pelapor,
            'telepon_pelapor' => $this->telepon_pelapor,

            // 🚢 Data Kapal
            'jenis_kapal' => $this->jenis_kapal,
            'nama_kapal' => $this->nama_kapal,
            'nama_kapal_kedua' => $this->nama_kapal_kedua,
            'bendera_kapal' => $this->bendera_kapal,
            'grt_kapal' => $this->grt_kapal,
            'imo_number' => $this->imo_number,

            // ⚓ Rute
            'pelabuhan_asal' => $this->pelabuhan_asal,
            'waktu_berangkat' => $this->waktu_berangkat,
            'pelabuhan_tujuan' => $this->pelabuhan_tujuan,
            'estimasi_tiba' => $this->estimasi_tiba,

            // 🤝 Pemilik & Agen
            'pemilik_kapal' => $this->pemilik_kapal,
            'kontak_pemilik' => $this->kontak_pemilik,
            'agen_lokal' => $this->agen_lokal,
            'kontak_agen' => $this->kontak_agen,

            // 🧭 Posisi & isi laporan
            'posisi_lintang' => $this->posisi_lintang,
            'posisi_bujur' => $this->posisi_bujur,
            'tanggal_laporan' => $this->tanggal_laporan,
            'isi_laporan' => $this->isi_laporan,

            // ⚠️ Jenis kecelakaan
            'jenis_kecelakaan' => $this->jenis_kecelakaan,
            'pihak_terkait' => $this->pihak_terkait,

            // 📎 Relasi
            'lampiran' => LampiranResource::collection($this->whenLoaded('lampiran')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}