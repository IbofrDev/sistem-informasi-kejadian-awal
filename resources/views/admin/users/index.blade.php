@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Daftar User')

@section('content')
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- Form Pencarian --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama atau email..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> <span class="d-none d-sm-inline">Cari</span>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($users as $user)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            {{-- Avatar Inisial Nama --}}
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <span class="fw-bold fs-5">{{ strtoupper(substr($user->nama, 0, 2)) }}</span>
                                </div>
                            </div>

                            {{-- Info Pengguna --}}
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ $user->nama }}</h5>
                                <p class="card-text text-muted small mb-0">{{ $user->email }}</p>
                            </div>
                        </div>

                        {{-- Statistik & Role --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-file-earmark-check me-1"></i>
                                    {{ $user->laporan_kejadian_count }} Laporan
                                </span>
                            </div>
                            <div>
                                <span class="badge {{ $user->role == 'admin' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        {{-- Tombol Detail --}}
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-person-lines-fill"></i> Detail
                        </a>

                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Anda yakin ingin menghapus user ini? Semua laporan yang terkait akan ikut terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            {{-- Pesan jika tidak ada user yang ditemukan --}}
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h5 class="alert-heading">Tidak Ditemukan</h5>
                    <p class="mb-0">Tidak ada user yang cocok dengan pencarian Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginasi --}}
    {{-- Paginasi (Custom Style Seperti Dashboard Admin) --}}
    @if ($users->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            <nav>
                <ul class="pagination mb-0">
                    {{-- Tombol Previous --}}
                    @if ($users->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link"><i class="bi bi-arrow-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">
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
        </div>
    @endif

@endsection