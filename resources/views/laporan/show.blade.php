@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Kejadian')

@section('content')
    {{-- Header Halaman dengan Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan Kejadian</h1>
        <div class="d-flex gap-2">
            @if(Auth::user()->role == 'admin')
                {{-- Tombol untuk Admin (jika perlu) --}}
                <button type="button" class="btn btn-sm btn-primary" title="Lihat Log Histori" data-bs-toggle="modal" data-bs-target="#logHistoryModal"><i class="bi bi-bell-fill me-1"></i> Log Histori</button>
                <a href="{{ route('admin.laporan.print', $laporan->id) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer-fill me-1"></i> Cetak PDF</a>
                <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline form-delete"> @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash me-1"></i> Hapus</button></form> 
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            @else
                {{-- Tombol untuk Pelapor --}}
                <button type="button" class="btn btn-primary" title="Lihat Log Histori Saya" data-bs-toggle="modal" data-bs-target="#logHistoryModal"><i class="bi bi-bell-fill me-1"></i> Log Histori</button>
                <a href="{{ route('laporan.print', $laporan->id) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer-fill me-1"></i> Cetak PDF</a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            @endif
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- == BAGIAN BARU: TIMELINE STATUS LAPORAN == --}}
    {{-- ============================================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Status Proses Laporan</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">

                {{-- Kolom 1: Dikirim --}}
                <div class="col-md-4">
                    @php
                        // Status 'dikirim' selalu aktif atau sudah lewat (abu-abu)
                        $dikirimClass = 'text-success'; // Default hijau
                        $dikirimIcon = 'bi-check-circle-fill';
                        if ($laporan->status_laporan == 'diverifikasi' || $laporan->status_laporan == 'selesai') {
                            $dikirimClass = 'text-muted'; // Jadi abu-abu jika sudah lewat
                        }
                    @endphp
                    <div class="{{ $dikirimClass }}">
                        <i class="bi {{ $dikirimIcon }} fs-2 mb-2 d-block"></i>
                        <h5 class="fw-bold">Dikirim</h5>
                        <small class="d-block">{{ $laporan->created_at->translatedFormat('d M Y, H:i') }}</small>
                    </div>
                </div>

                {{-- Kolom 2: Diverifikasi --}}
                <div class="col-md-4">
                    @php
                        $verifikasiClass = 'text-muted'; // Default abu-abu
                        $verifikasiIcon = 'bi-hourglass-split'; // Icon default (belum)
                        $verifikasiTimestamp = '-';

                        if ($verifiedAt) { // Jika sudah diverifikasi (ada timestamp)
                            if ($laporan->status_laporan == 'diverifikasi') {
                                $verifikasiClass = 'text-warning'; // Kuning jika aktif
                                $verifikasiIcon = 'bi-check-circle-fill';
                            } elseif ($laporan->status_laporan == 'selesai') {
                                $verifikasiClass = 'text-muted'; // Abu-abu jika sudah lewat
                                $verifikasiIcon = 'bi-check-circle-fill';
                            }
                            $verifikasiTimestamp = $verifiedAt->translatedFormat('d M Y, H:i');
                        } elseif ($laporan->status_laporan == 'diverifikasi') {
                            // Kasus aneh jika status diverifikasi tapi log tidak ada, tampilkan saja
                            $verifikasiClass = 'text-warning';
                            $verifikasiIcon = 'bi-check-circle-fill';
                            $verifikasiTimestamp = 'Status saat ini';
                        }
                    @endphp
                    <div class="{{ $verifikasiClass }}">
                        <i class="bi {{ $verifikasiIcon }} fs-2 mb-2 d-block"></i>
                        <h5 class="fw-bold">Diverifikasi</h5>
                        <small class="d-block">{{ $verifikasiTimestamp }}</small>
                    </div>
                </div>

                {{-- Kolom 3: Selesai --}}
                <div class="col-md-4">
                    @php
                        $selesaiClass = 'text-muted'; // Default abu-abu
                        $selesaiIcon = 'bi-hourglass-split'; // Icon default (belum)
                        $selesaiTimestamp = '-';

                        if ($completedAt) { // Jika sudah selesai (ada timestamp)
                            $selesaiClass = 'text-success'; // Hijau jika selesai
                            $selesaiIcon = 'bi-check-all'; // Icon berbeda untuk selesai
                            $selesaiTimestamp = $completedAt->translatedFormat('d M Y, H:i');
                        } elseif ($laporan->status_laporan == 'selesai' && !$completedAt) {
                            // Kasus aneh jika status selesai tapi log tidak ada
                            $selesaiClass = 'text-success';
                            $selesaiIcon = 'bi-check-all';
                            $selesaiTimestamp = 'Status saat ini';
                        }
                    @endphp
                    <div class="{{ $selesaiClass }}">
                        <i class="bi {{ $selesaiIcon }} fs-2 mb-2 d-block"></i>
                        <h5 class="fw-bold">Selesai</h5>
                        <small class="d-block">{{ $selesaiTimestamp }}</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- ============================================= --}}
    {{-- == AKHIR BAGIAN BARU == --}}
    {{-- ============================================= --}}


    {{-- Card Informasi Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Informasi Laporan</h6>
        </div>
        <div class="card-body">
            <table class="table"> {{-- Menggunakan class table saja --}}
                <tbody> 
                    <tr>
                        <td class="fw-bold text-dark" style="width: 20%;">Nama Pelapor</td>
                        <td style="width: 80%;">: {{ $laporan->nama_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Telepon</td>
                        <td>: {{ $laporan->telepon_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Jabatan</td>
                        <td>: {{ $laporan->jabatan_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Nama Kapal</td>
                        <td>: {{ $laporan->nama_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Jenis Kapal</td>
                        <td>: {{ $laporan->jenis_kapal ?? 'N/A' }}</td>
                    </tr>
                    
                    {{-- KODE BARU DIMASUKKAN DI SINI --}}
                    <tr>
                        <td class="fw-bold text-dark">Jenis Kecelakaan</td>
                        <td>: {{ $laporan->jenis_kecelakaan ?? 'N/A' }}</td>
                    </tr>
                    {{-- AKHIR KODE BARU --}}

                    <tr>
                        <td class="fw-bold text-dark">Bendera</td>
                        <td>: {{ $laporan->bendera_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">GRT</td>
                        <td>: {{ $laporan->grt_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Posisi Lintang</td>
                        <td>: {{ $laporan->posisi_lintang ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Posisi Bujur</td>
                        <td>: {{ $laporan->posisi_bujur ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Tanggal Kejadian</td>
                        <td>: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Status Laporan</td>
                        <td>:
                            @if($laporan->status_laporan == 'diverifikasi')
                                <span class="badge bg-warning text-dark">{{ ucfirst($laporan->status_laporan) }}</span>
                            @elseif($laporan->status_laporan == 'selesai')
                                <span class="badge bg-success">{{ ucfirst($laporan->status_laporan) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($laporan->status_laporan) }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card Isi Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Isi Laporan</h6>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;">{{ $laporan->isi_laporan }}</p>
        </div>
    </div>

    {{-- Card Lampiran --}}
    @if($laporan->lampiran->isNotEmpty())
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Lampiran</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($laporan->lampiran as $file)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            @if($file->tipe_file == 'foto')
                                <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $file->path_file) }}" 
                                         class="card-img-top rounded-top" alt="Lampiran Foto" 
                                         style="height: 180px; width: 100%; object-fit: cover;">
                                </a>
                            @else
                                <video controls class="card-img-top rounded-top" style="height: 180px; width: 100%; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $file->path_file) }}" type="{{ mime_content_type(storage_path('app/public/' . $file->path_file)) }}">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    <div class="modal fade" id="logHistoryModal" tabindex="-1" aria-labelledby="logHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logHistoryModalLabel">Log Histori Laporan #{{ $laporan->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        @forelse($activities as $activity)
                            <li class="list-group-item border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">{{ $activity->causer->nama ?? 'Sistem' }}</span>
                                    <span class="text-muted" style="font-size: 0.85rem;">{{ $activity->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ $activity->description }}</p>
                                @if($activity->event === 'updated' && $activity->properties->has('old'))
                                    <div class="bg-light p-3 rounded" style="font-size: 0.85rem;">
                                        <strong>Perubahan:</strong>
                                        <ul class="list-unstyled mb-0 mt-1">
                                            @foreach($activity->properties['old'] as $key => $value)
                                                @if ($key == 'isi_laporan')
                                                    <li>
                                                        <i class="bi bi-pencil-fill text-info me-1"></i>
                                                        Isi Laporan diubah:
                                                        <div class="card mt-2">
                                                            <div class="card-header py-1 px-2 text-danger"><strong>SEBELUM:</strong></div>
                                                            <div class="card-body py-2 px-2 text-muted" style="white-space: pre-wrap;">{{ $value }}</div>
                                                        </div>
                                                        <div class="card mt-2">
                                                            <div class="card-header py-1 px-2 text-success"><strong>SESUDAH:</strong></div>
                                                            <div class="card-body py-2 px-2" style="white-space: pre-wrap;">{{ $activity->properties['attributes'][$key] }}</div>
                                                        </div>
                                                    </li>
                                                @elseif ($key != 'status_laporan') 
                                                    <li>
                                                        <i class="bi bi-dot"></i>
                                                        Kolom <code>{{ $key }}</code> diubah dari
                                                        <strong class="text-danger">"{{ Str::limit($value, 50) }}"</strong>
                                                        menjadi
                                                        <strong class="text-success">"{{ Str::limit($activity->properties['attributes'][$key], 50) }}"</strong>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted p-4">
                                Belum ada riwayat aktivitas yang Anda lakukan untuk laporan ini.
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endsection