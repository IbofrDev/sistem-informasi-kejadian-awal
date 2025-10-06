@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
    <style>
        /* Pastikan semua text header kartu tidak terpecah dan tersusun rapi */
        .card-stat .text-header {
            white-space: nowrap;
            font-size: 0.9rem;
            /* 🔥 diperbesar dari 0.75rem */
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            letter-spacing: 0.5px;
            /* opsional, bikin teks lebih rapi */
        }

        .card-stat .icon {
            font-size: 2rem;
            color: #6c757d;
        }

        .card-stat .value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #343a40;
        }
    </style>

    {{-- Baris untuk Kartu Statistik --}}
    <div class="row">
        {{-- Total Laporan --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-primary shadow-sm h-100 py-2 card-stat">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-header text-primary">Total Laporan</div>
                        <div class="value">{{ $totalLaporan }}</div>
                    </div>
                    <i class="bi bi-file-earmark-text-fill icon"></i>
                </div>
            </div>
        </div>

        {{-- Laporan Baru --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-info shadow-sm h-100 py-2 card-stat">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-header text-info">Laporan Baru</div>
                        <div class="value">{{ $laporanBaru }}</div>
                    </div>
                    <i class="bi bi-inbox-fill icon"></i>
                </div>
            </div>
        </div>

        {{-- Perlu Diverifikasi --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-warning shadow-sm h-100 py-2 card-stat">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-header text-warning">Perlu Diverifikasi</div>
                        <div class="value">{{ $perluVerifikasi }}</div>
                    </div>
                    <i class="bi bi-patch-question-fill icon"></i>
                </div>
            </div>
        </div>

        {{-- Laporan Selesai --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-success shadow-sm h-100 py-2 card-stat">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-header text-success">Laporan Selesai</div>
                        <div class="value">{{ $laporanSelesai }}</div>
                    </div>
                    <i class="bi bi-check-circle-fill icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Daftar Laporan --}}
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Daftar Laporan Kejadian</h6>

            {{-- Grup Form Search + Filter --}}
            <div class="d-flex flex-wrap align-items-center gap-2">
                {{-- Form Pencarian --}}
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex">
                    @if ($selectedStatus)
                        <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    @endif

                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari ID, Nama Kapal, Jenis Kapal, atau Tanggal..." value="{{ request('search') }}">

                    <button type="submit" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </form>

                {{-- Grup Tombol Filter Status --}}
                <div class="btn-group" role="group" aria-label="Filter Status Laporan">
                    <a href="{{ route('admin.dashboard') }}"
                        class="btn btn-sm {{ !$selectedStatus ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'dikirim']) }}"
                        class="btn btn-sm {{ $selectedStatus == 'dikirim' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Dikirim
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'diverifikasi']) }}"
                        class="btn btn-sm {{ $selectedStatus == 'diverifikasi' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Diverifikasi
                    </a>
                    <a href="{{ route('admin.dashboard', ['status' => 'selesai']) }}"
                        class="btn btn-sm {{ $selectedStatus == 'selesai' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Selesai
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelapor</th>
                            <th>Tanggal Kejadian</th>
                            <th>Jenis Kapal</th>
                            <th>Nama Kapal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaLaporan as $laporan)
                            <tr>
                                <td>#{{ $laporan->id }}</td>
                                <td>{{ $laporan->user->nama ?? $laporan->nama_pelapor ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y, H:i') }}</td>
                                <td>{{ $laporan->jenis_kapal ?? '-' }}</td>
                                <td>{{ $laporan->nama_kapal ?? '-' }}</td>
                                <td>
                                    @if($laporan->status_laporan == 'diverifikasi')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @elseif($laporan->status_laporan == 'selesai')
                                        <span class="badge bg-success">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($laporan->status_laporan) }}</span>
                                    @endif
                                </td>
                                <td class="text-center d-flex justify-content-center gap-1">
                                    <form action="{{ route('admin.laporan.updateStatus', $laporan->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto"
                                            onchange="this.form.submit()">
                                            <option value="dikirim" {{ $laporan->status_laporan == 'dikirim' ? 'selected' : '' }}>
                                                Dikirim</option>
                                            <option value="diverifikasi" {{ $laporan->status_laporan == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                            <option value="selesai" {{ $laporan->status_laporan == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                        </select>
                                    </form>

                                    <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn btn-sm btn-info"
                                        title="Detail"><i class="bi bi-eye-fill"></i></a>

                                    <a href="{{ route('admin.laporan.print', $laporan->id) }}" class="btn btn-sm btn-secondary"
                                        title="Cetak PDF"><i class="bi bi-printer-fill"></i></a>

                                    <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    Tidak ada data laporan untuk pencarian atau filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Link Paginasi --}}
            <div class="mt-3 d-flex justify-content-center">
                @if ($semuaLaporan->hasPages())
                    <nav>
                        <ul class="pagination mb-0">
                            @if ($semuaLaporan->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link"><i class="bi bi-arrow-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $semuaLaporan->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-arrow-left"></i>
                                    </a>
                                </li>
                            @endif

                            @foreach ($semuaLaporan->getUrlRange(1, $semuaLaporan->lastPage()) as $page => $url)
                                @if ($page == $semuaLaporan->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if ($semuaLaporan->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $semuaLaporan->nextPageUrl() }}" rel="next">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link"><i class="bi bi-arrow-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection