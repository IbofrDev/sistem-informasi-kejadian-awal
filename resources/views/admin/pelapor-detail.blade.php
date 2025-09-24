@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
    {{-- Header dengan tombol --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Detail User</h2>
            <small class="text-muted">Melihat informasi dan riwayat laporan dari {{ $pelapor->nama }}.</small>
        </div>
        <div>
            <a href="{{ route('admin.users.edit', $pelapor) }}" class="btn btn-sm btn-outline-primary">Edit User</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>

    {{-- BAGIAN 1: KARTU DETAIL USER --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex align-items-center">
            {{-- Avatar Inisial Nama --}}
            <div class="flex-shrink-0 me-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4">{{ strtoupper(substr($pelapor->nama ?? 'U', 0, 2)) }}</span>
                </div>
            </div>

            {{-- Info utama --}}
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $pelapor->nama }}</h4>
                <p class="text-muted mb-1">{{ $pelapor->email }}</p>
                <span class="badge bg-secondary">{{ ucfirst($pelapor->jabatan ?? 'User') }}</span>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted d-block">Jenis Kapal</small>
                    <span class="fw-bold">{{ $pelapor->jenis_kapal ?? '-' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Nomor Telepon</small>
                    <span class="fw-bold">{{ $pelapor->phone_number ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: RIWAYAT LAPORAN --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Laporan</h5>
            <span class="badge bg-light text-dark border">{{ $laporanKejadian->count() }} laporan</span>
        </div>
        <div class="card-body">
            @forelse($laporanKejadian as $laporan)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                    <div>
                        <h6 class="mb-1">{{ $laporan->nama_kapal }}</h6>
                        <small class="text-muted">#{{ str_pad($laporan->id, 6, '0', STR_PAD_LEFT) }} •
                            {{ $laporan->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="text-end">
                        <span
                            class="badge {{ $laporan->status_laporan == 'dikirim' ? 'bg-primary' : ($laporan->status_laporan == 'diverifikasi' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($laporan->status_laporan) }}
                        </span>
                        <a href="{{ route('admin.laporan.show', $laporan) }}" class="btn btn-sm btn-outline-info ms-2">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted my-4">Pelapor ini belum membuat laporan.</p>
            @endforelse
        </div>
    </div>
@endsection
