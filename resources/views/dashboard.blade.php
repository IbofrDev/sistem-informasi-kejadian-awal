@extends('layouts.app')

@section('title', 'Dashboard Pelapor')
@section('page-title', 'Dashboard Anda')

@section('content')
    {{-- Kartu Sambutan dan Aksi Cepat --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="card-text text-muted">Di sini Anda dapat melihat riwayat laporan yang telah Anda buat dan mengelola
                laporan Anda.</p>
            <a href="{{ route('laporan.create') }}" class="btn btn-warning">
                <i class="bi bi-plus-circle-fill me-2"></i>Buat Laporan Baru
            </a>
        </div>
    </div>

    {{-- Filter Bulan & Tahun --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-sm-6">
                    <label for="bulan" class="form-label mb-1">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label for="tahun" class="form-label mb-1">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat Laporan --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h6 class="m-0 fw-bold text-primary">Riwayat Laporan Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Kejadian</th>
                            <th>Jenis Kapal</th>
                            <th>Nama Kapal</th>
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
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y') }}</td>
                                <td>{{ $laporan->jenis_kapal ?? '-' }}</td>
                                <td>{{ $laporan->nama_kapal ?? '-' }}</td>
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
                                <td class="text-center d-flex justify-content-center gap-1">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('laporan.show', $laporan->id) }}" class="btn btn-sm btn-info"
                                        title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- Tombol Edit (kalau belum selesai) --}}
                                    @if($laporan->status_laporan != 'selesai')
                                        <a href="{{ route('laporan.edit', $laporan->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit Laporan">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    @endif

                                    {{-- 🔹 Tombol Cetak PDF --}}
                                    <a href="{{ route('laporan.print', $laporan->id) }}" class="btn btn-sm btn-secondary"
                                        title="Cetak PDF">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- Pesan jika tidak ada laporan --}}
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="mb-2">Tidak ada laporan untuk bulan/tahun yang dipilih atau Anda belum membuat laporan.</p>
                                    <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Pertama Anda
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection