@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id)
@section('page-title', 'Detail Laporan Kejadian')

@section('content')
    {{-- Header Halaman dengan Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan Kejadian</h1>
        
        <div class="d-flex gap-2">
            
            <button 
                type="button" 
                class="btn btn-primary" 
                title="Lihat Log Histori"
                data-bs-toggle="modal" 
                data-bs-target="#logHistoryModal">
                <i class="bi bi-bell-fill me-1"></i> Log Histori
            </button>

            <a href="{{ route('admin.laporan.print', $laporan->id) }}" target="_blank" class="btn btn-success">
                <i class="bi bi-printer-fill me-1"></i> Cetak PDF
            </a>

            <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline form-delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form> 

            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Card Informasi Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Informasi Laporan</h6>
        </div>
        <div class="card-body">
            <table class="table"> 
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
                        <td>: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                    </tr>

                    {{-- =================================== --}}
                    {{-- ==        KODE YANG DITAMBAHKAN      == --}}
                    {{-- =================================== --}}
                    <tr>
                        <td class="fw-bold text-dark">Jenis Kecelakaan</td>
                        <td>: {{ $laporan->jenis_kecelakaan ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">Pihak Terkait</td>
                        <td>: {{ $laporan->pihak_terkait ?? 'N/A' }}</td>
                    </tr>
                    {{-- =================================== --}}
                    {{-- ==      AKHIR DARI KODE BARU       == --}}
                    {{-- =================================== --}}

                    <tr>
                        <td class="fw-bold text-dark">Status</td>
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
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Lampiran</h6>
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
                                                @if ($key == 'status_laporan')
                                                    <li>
                                                        <i class="bi bi-check-circle-fill text-warning me-1"></i>
                                                        Status diubah dari
                                                        <strong class="text-danger">"{{ $value }}"</strong>
                                                        menjadi
                                                        <strong class="text-success">"{{ $activity->properties['attributes'][$key] }}"</strong>
                                                    </li>
                                                @elseif ($key == 'isi_laporan')
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
                                                @else
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
                                Belum ada riwayat aktivitas untuk laporan ini.
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