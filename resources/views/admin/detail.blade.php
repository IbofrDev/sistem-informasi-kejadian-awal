@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id)
@section('page-title', 'Detail Laporan Kejadian')

@section('content')
    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan Kejadian</h1>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logHistoryModal">
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

{{-- INFORMASI PELAPOR --}}
<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-dark">Informasi Pelapor</h6>
    </div>
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tbody>
                <tr>
                    <td class="fw-bold" style="width: 25%">Perusahaan / PT</td>
                    <td>: {{ $laporan->user?->pt ?? 'Tidak Diketahui' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Nama Pelapor</td>
                    <td>: {{ $laporan->nama_pelapor ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Jabatan</td>
                    <td>: {{ $laporan->jabatan_pelapor ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Telepon</td>
                    <td>: {{ $laporan->telepon_pelapor ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    {{-- INFORMASI KAPAL & PERJALANAN --}}
    <div class="card shadow-sm mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 fw-bold text-dark">Informasi Kapal &amp; Perjalanan</h6>
  </div>
  <div class="card-body">
    <div class="row">
      {{-- Kolom kiri: Informasi Kapal --}}
      <div class="col-lg-6 col-md-12">
        <table class="table table-borderless mb-0">
          <tbody>
            <tr><td class="fw-bold">Nama Kapal</td><td>: {{ $laporan->nama_kapal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Nama Kapal Kedua</td><td>: {{ $laporan->nama_kapal_kedua ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Jenis Kapal</td><td>: {{ $laporan->jenis_kapal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Bendera</td><td>: {{ $laporan->bendera_kapal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">GRT</td><td>: {{ $laporan->grt_kapal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">IMO Number</td><td>: {{ $laporan->imo_number ?? '-' }}</td></tr>
          </tbody>
        </table>
      </div>

      {{-- Kolom kanan: Perjalanan --}}
      <div class="col-lg-6 col-md-12">
        <table class="table table-borderless mb-0">
          <tbody>
            <tr><td colspan="2" class="fw-bold text-primary">Perjalanan</td></tr>
            <tr><td class="fw-bold">Pelabuhan Asal</td><td>: {{ $laporan->pelabuhan_asal ?: '-' }}</td></tr>
            <tr><td class="fw-bold">Waktu Berangkat</td>
                <td>: {{ $laporan->waktu_berangkat?->translatedFormat('d F Y, H:i') ?? '-' }}</td>
            </tr>
            <tr><td class="fw-bold">Pelabuhan Tujuan</td><td>: {{ $laporan->pelabuhan_tujuan ?: '-' }}</td></tr>
            <tr><td class="fw-bold">Estimasi Tiba</td>
              <td>: {{ $laporan->estimasi_tiba?->translatedFormat('d F Y, H:i') ?? '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

 {{-- PEMILIK, AGEN, PILOT & MUATAN --}}
<div class="card shadow-sm mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 fw-bold text-dark">Pemilik, Agen, Pandu &amp; Muatan</h6>
  </div>
  <div class="card-body">
    <div class="row g-3">
      {{-- Kiri: Pemilik, Agen, dan Pandu --}}
      <div class="col-lg-6 col-md-12">
        <table class="table table-borderless mb-0">
          <tbody>
            <tr><td class="fw-bold">Pemilik Kapal</td><td>: {{ $laporan->pemilik_kapal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Kontak Pemilik</td><td>: {{ $laporan->kontak_pemilik ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Agen Lokal</td><td>: {{ $laporan->agen_lokal ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Kontak Agen</td><td>: {{ $laporan->kontak_agen ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Nama Pandu</td><td>: {{ $laporan->nama_pandu ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Nomor Register Pandu</td><td>: {{ $laporan->nomor_register_pandu ?? '-' }}</td></tr>
          </tbody>
        </table>
      </div>

      {{-- Kanan: Muatan --}}
      <div class="col-lg-6 col-md-12">
        <table class="table table-borderless mb-0">
          <tbody>
            <tr><td class="fw-bold text-primary" colspan="2">Muatan</td></tr>
            <tr><td class="fw-bold">Jenis Muatan</td><td>: {{ $laporan->jenis_muatan ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Jumlah Muatan</td><td>: {{ $laporan->jumlah_muatan ?? '-' }}</td></tr>
            <tr><td class="fw-bold">Jumlah Penumpang</td><td>: {{ $laporan->jumlah_penumpang ?? '-' }}</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

    {{-- DETAIL KEJADIAN --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Detail Kejadian</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tbody>
                    <tr><td class="fw-bold">Posisi Lintang</td><td>: {{ $laporan->posisi_lintang ?? 'N/A' }}</td></tr>
                    <tr><td class="fw-bold">Posisi Bujur</td><td>: {{ $laporan->posisi_bujur ?? 'N/A' }}</td></tr>
                    <tr><td class="fw-bold">Tanggal Kejadian</td>
                        <td>: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                    </tr>
                    <tr><td class="fw-bold">Jenis Kecelakaan</td><td>: {{ $laporan->jenis_kecelakaan ?? '-' }}</td></tr>
                    <tr><td class="fw-bold">Pihak Terkait</td><td>: {{ $laporan->pihak_terkait ?? '-' }}</td></tr>
                    <tr><td class="fw-bold">Status</td>
                        <td>:
                            @php $status = $laporan->status_laporan; @endphp
                            @if($status === 'diverifikasi')
                                <span class="badge bg-warning text-dark">{{ ucfirst($status) }}</span>
                            @elseif($status === 'selesai')
                                <span class="badge bg-success">{{ ucfirst($status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- 
              BAGIAN ISI LAPORAN SUDAH DIPINDAHKAN DARI SINI
            --}}

        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Isi Laporan</h6>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;">{{ $laporan->isi_laporan }}</p>
        </div>
    </div>
    {{-- LAMPIRAN FOTO & VIDEO --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-dark">Lampiran</h6>
        </div>
        <div class="card-body">
            @if($laporan->lampiran && $laporan->lampiran->count() > 0)
                <div class="row g-3">
                    @foreach($laporan->lampiran as $file)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @if($file->tipe_file === 'foto')
                                <a href="{{ asset('storage/'.$file->path_file) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$file->path_file) }}" class="img-fluid rounded" alt="Lampiran Foto"
                                         style="height:180px;width:100%;object-fit:cover;">
                                </a>
                            @elseif($file->tipe_file === 'video')
                                <video controls class="img-fluid rounded" style="height:180px;width:100%;object-fit:cover;">
                                    <source src="{{ asset('storage/'.$file->path_file) }}"
                                            type="{{ mime_content_type(storage_path('app/public/'.$file->path_file)) }}">
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

    {{-- MODAL LOG HISTORI --}}
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
                                    <span class="text-muted" style="font-size:0.85rem;">{{ $activity->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-muted mb-2" style="font-size:0.9rem;">{{ $activity->description }}</p>
                                @if($activity->event === 'updated' && $activity->properties->has('old'))
                                    <div class="bg-light p-3 rounded" style="font-size:0.85rem;">
                                        <strong>Perubahan:</strong>
                                        <ul class="list-unstyled mb-0 mt-1">
                                            @foreach($activity->properties['old'] as $key => $value)
                                                @if($key == 'status_laporan')
                                                    <li><i class="bi bi-check-circle-fill text-warning me-1"></i>
                                                        Status diubah dari
                                                        <strong class="text-danger">"{{ $value }}"</strong>
                                                        menjadi
                                                        <strong class="text-success">"{{ $activity->properties['attributes'][$key] }}"</strong>
                                                    </li>
                                                @elseif($key == 'isi_laporan')
                                                    <li><i class="bi bi-pencil-fill text-info me-1"></i>
                                                        Isi Laporan diubah:
                                                        <div class="card mt-2">
                                                            <div class="card-header py-1 px-2 text-danger"><strong>SEBELUM:</strong></div>
                                                            <div class="card-body py-2 px-2 text-muted" style="white-space:pre-wrap;">{{ $value }}</div>
                                                        </div>
                                                        <div class="card mt-2">
                                                            <div class="card-header py-1 px-2 text-success"><strong>SESUDAH:</strong></div>
                                                            <div class="card-body py-2 px-2" style="white-space:pre-wrap;">{{ $activity->properties['attributes'][$key] }}</div>
                                                        </div>
                                                    </li>
                    
                                                @else
                                                    <li><i class="bi bi-dot"></i>
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