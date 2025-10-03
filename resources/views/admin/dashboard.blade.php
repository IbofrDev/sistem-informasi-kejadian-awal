@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
    {{-- Baris untuk Kartu Statistik --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Laporan</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalLaporan }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-file-earmark-text-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Laporan Baru (Dikirim) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Laporan Baru</div>
                            {{-- Anda perlu mengirimkan variabel $laporanBaru dari controller --}}
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $laporanBaru }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-inbox-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Perlu Diverifikasi</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $perluVerifikasi }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-patch-question-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Laporan Selesai</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $laporanSelesai }}</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-check-circle-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Daftar Laporan --}}
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Daftar Laporan Kejadian</h6>

            {{-- Grup Tombol Filter --}}
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelapor</th>
                            <th>Tanggal Kejadian</th>
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
    {{-- Dropdown untuk ubah status --}}
    <form action="{{ route('admin.laporan.updateStatus', $laporan->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <select name="status" class="form-select form-select-sm d-inline-block w-auto"
                onchange="this.form.submit()">
            <option value="dikirim" {{ $laporan->status_laporan == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="diverifikasi" {{ $laporan->status_laporan == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
            <option value="selesai" {{ $laporan->status_laporan == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
    </form>

    {{-- Tombol Detail --}}
    <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn btn-sm btn-info"
       title="Detail"><i class="bi bi-eye-fill"></i></a>

    {{-- Tombol Cetak PDF --}}
    <a href="{{ route('admin.laporan.print', $laporan->id) }}" class="btn btn-sm btn-secondary"
       title="Cetak PDF"><i class="bi bi-printer-fill"></i></a>

    {{-- Tombol Hapus (pakai class form-delete agar pakai SweetAlert) --}}
    <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline form-delete">
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
                                <td colspan="5" class="text-center py-4">Tidak ada data laporan.</td>
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
                            {{-- Tombol Previous --}}
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

                            {{-- Nomor Halaman --}}
                            @foreach ($semuaLaporan->getUrlRange(1, $semuaLaporan->lastPage()) as $page => $url)
                                @if ($page == $semuaLaporan->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Tombol Next --}}
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
