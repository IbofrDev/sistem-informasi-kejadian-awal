@extends('layouts.app')

@section('title', 'Dashboard Pelapor')
@section('page-title', 'Dashboard Anda')

@section('content')
    {{-- Kartu Sambutan dan Aksi Cepat --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="card-text text-muted">Di sini Anda dapat melihat riwayat laporan yang telah Anda buat dan mengelola laporan Anda.</p>
            <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-2"></i>Buat Laporan Baru
            </a>
        </div>
    </div>

    {{-- Tabel Riwayat Laporan --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h6 class="m-0 fw-bold text-primary">Riwayat Laporan Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop untuk setiap laporan milik user --}}
                        @forelse ($laporanKejadian as $laporan)
                            <tr>
                                <td>#{{ $laporan->id }}</td>
                                <td>{{ $laporan->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    {{-- Badge status dengan warna berbeda --}}
                                    @if($laporan->status_laporan == 'diverifikasi')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @elseif($laporan->status_laporan == 'selesai')
                                        <span class="badge bg-success">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('laporan.show', $laporan->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    {{-- Tombol Edit hanya muncul jika status belum selesai --}}
                                    @if($laporan->status_laporan != 'selesai')
                                    <a href="{{ route('laporan.edit', $laporan->id) }}" class="btn btn-sm btn-warning" title="Edit Laporan">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            {{-- Pesan jika tidak ada laporan --}}
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="mb-2">Anda belum membuat laporan apapun.</p>
                                    <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-primary">Buat Laporan Pertama Anda</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection