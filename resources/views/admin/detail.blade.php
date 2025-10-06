@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id)
@section('page-title', 'Detail Laporan Kejadian')

@section('content')
    {{-- Header Halaman dengan Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan Kejadian</h1>
        <div class="d-flex gap-2">
            {{-- Tombol Cetak PDF --}}
            <a href="{{ route('admin.laporan.print', $laporan->id) }}" target="_blank" class="btn btn-success">
                <i class="bi bi-printer-fill me-1"></i> Cetak PDF
            </a>

            {{-- Tombol Hapus (pakai class form-delete agar kena SweetAlert2 global) --}}
            <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline form-delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>

            {{-- Tombol Kembali --}}
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Card Informasi Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Informasi Laporan</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="fw-bold" style="width: 20%;">Nama Pelapor</td>
                        <td style="width: 80%;">: {{ $laporan->nama_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Telepon</td>
                        <td>: {{ $laporan->telepon_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jabatan</td>
                        <td>: {{ $laporan->jabatan_pelapor ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama Kapal</td>
                        <td>: {{ $laporan->nama_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jenis Kapal</td>
                        <td>: {{ $laporan->jenis_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Bendera</td>
                        <td>: {{ $laporan->bendera_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">GRT</td>
                        <td>: {{ $laporan->grt_kapal ?? 'N/A' }}</td>
                    </tr>
                    <tr class="table-light">
                        <td class="fw-bold">Posisi Lintang</td>
                        <td>: {{ $laporan->posisi_lintang ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Posisi Bujur</td>
                        <td>: {{ $laporan->posisi_bujur ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Kejadian</td>
                        <td>: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status</td>
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
            <h6 class="m-0 fw-bold text-primary">Isi Laporan</h6>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;">{{ $laporan->isi_laporan }}</p>
        </div>
    </div>

    {{-- Card Lampiran --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Lampiran</h6>
        </div>
        <div class="card-body">
            @if ($laporan->lampiran && $laporan->lampiran->count() > 0)
                <div class="row g-3">
                    @foreach ($laporan->lampiran as $file)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @if ($file->tipe_file == 'foto')
                                <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $file->path_file) }}" class="img-fluid rounded" alt="Lampiran Foto" style="height: 180px; width: 100%; object-fit: cover;">
                                </a>
                            @elseif ($file->tipe_file == 'video')
                                <video controls class="img-fluid rounded" style="height: 180px; width: 100%; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $file->path_file) }}" type="{{ mime_content_type(storage_path('app/public/' . $file->path_file)) }}">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">Tidak ada lampiran yang diunggah.</p>
            @endif
        </div>
    </div>
@endsection