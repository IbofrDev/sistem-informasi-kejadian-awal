@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Kejadian')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary">Detail Laporan Kejadian</h5>
            <div class="btn-group">
                @if(Auth::user()->role == 'admin')
                    {{-- Tombol Cetak untuk Admin --}}
                    <a href="{{ route('admin.laporan.print', $laporan->id) }}" target="_blank" 
                       class="btn btn-sm btn-success">
                        <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                @else
                    {{-- Tombol Cetak untuk Pelapor --}}
                    <a href="{{ route('laporan.print', $laporan->id) }}" target="_blank" 
                       class="btn btn-sm btn-success">
                        <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                @endif
            </div>
        </div>

        {{-- Card Detail --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-bold text-primary">
                Informasi Laporan
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <tr>
                        <th style="width: 20%">Nama Pelapor</th>
                        <td>{{ $laporan->nama_pelapor }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $laporan->telepon_pelapor }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $laporan->jabatan_pelapor }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kapal</th>
                        <td>{{ $laporan->nama_kapal }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kapal</th>
                        <td>{{ $laporan->jenis_kapal }}</td>
                    </tr>
                    <tr>
                        <th>Bendera</th>
                        <td>{{ $laporan->bendera_kapal }}</td>
                    </tr>
                    <tr>
                        <th>GRT</th>
                        <td>{{ $laporan->grt_kapal }}</td>
                    </tr>
                    <tr>
                        <th>Posisi Lintang</th>
                        <td>{{ $laporan->posisi_lintang }}</td>
                    </tr>
                    <tr>
                        <th>Posisi Bujur</th>
                        <td>{{ $laporan->posisi_bujur }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Kejadian</th>
                        <td>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Isi Laporan --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light fw-bold text-primary">Isi Laporan</div>
            <div class="card-body">
                <div class="p-3 bg-light rounded">{{ $laporan->isi_laporan }}</div>
            </div>
        </div>

        {{-- Lampiran --}}
        @if($laporan->lampiran->isNotEmpty())
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light fw-bold text-primary">Lampiran</div>
            <div class="card-body">
                <div class="row">
                    @foreach($laporan->lampiran as $file)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                @if($file->tipe_file == 'foto')
                                    <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $file->path_file) }}" 
                                             class="card-img-top rounded-top" alt="Lampiran Foto">
                                    </a>
                                @else
                                    <div class="card-body text-center">
                                        <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="bi bi-camera-video me-1"></i> Lihat Video
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection