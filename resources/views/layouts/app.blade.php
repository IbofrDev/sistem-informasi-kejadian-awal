<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kejadian Awal')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .nav-pills .nav-link.active {
            background-color: #E9C217 !important;
            color: #1f1f1f !important;
        }

        /* Style untuk dropdown notifikasi */
        .dropdown-menu-notif {
            width: 350px !important;
            max-height: 400px;
            overflow-y: auto;
        }
        .notif-item .notif-content {
            white-space: normal; /* Agar teks bisa wrap */
            font-size: 0.9rem;
        }
    </style>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="d-flex">
        <nav class="sidebar vh-100 text-white p-3 shadow-sm"
            style="width: 280px; position: fixed; background: linear-gradient(to bottom, #28166F, #120A30);">
            <a href="/" class="d-flex align-items-center mb-4 text-white text-decoration-none">
                <img src="{{ asset('images/logo_ksop.png') }}" alt="Logo KSOP" style="width: 40px; height: auto;"
                    class="me-3">
                <div>
                    <span class="fs-5 fw-bold d-block" style="line-height: 1;">SIKAP</span>
                    <small class="text-white-50" style="font-size: 0.8rem; line-height: 1;">
                        Sistem Informasi Kecelakaan Kapal
                    </small>
                </div>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">

                @if (Auth::user()->role == 'admin')
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> <strong>Dashboard</strong>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <i class="bi bi-people-fill me-2"></i> <strong>Kelola User</strong>
                        </a>
                    </li>
                    
                    <!-- 🔽 TAMBAHKAN KODE INI 🔽 -->
                    <li class="nav-item mb-1">
                        <a href="{{ route('laporan.create') }}"
                            class="nav-link text-white {{ request()->routeIs('laporan.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle-fill me-2"></i> <strong>Buat Laporan Baru</strong>
                        </a>
                    </li>
                    <!-- 🔼 AKHIR DARI KODE BARU 🔼 -->

                @else
                    <li class="nav-item mb-1">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text-fill me-2"></i> Laporan Saya
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('laporan.create') }}"
                            class="nav-link text-white {{ request()->routeIs('laporan.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle-fill me-2"></i> Buat Laporan Baru
                        </a>
                    </li>
                @endif

            </ul>
        </nav>

        <main class="main-content" style="margin-left: 280px; width: calc(100% - 280px);">
            <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                <div class="container-fluid">
                    <h4 class="mb-0 fw-bold">@yield('page-title', 'Halaman Utama')</h4>

                    <!-- Wrapper untuk item navbar di kanan -->
                    <div class="d-flex align-items-center ms-auto">

                        <!-- 1. Dropdown Notifikasi (HANYA UNTUK ADMIN) -->
                        @if (Auth::user()->role == 'admin')
                            <div class="dropdown me-3">
                                <!-- Tombol Lonceng -->
                                <a href="#" class="d-block link-dark text-decoration-none position-relative"
                                    data-bs-toggle="dropdown" aria-expanded="false" title="Laporan Baru">
                                    <i class="bi bi-bell-fill fs-4"></i>
                                    
                                    <!-- Badge Jumlah Laporan Baru -->
                                    @if ($laporanBaruCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                                            {{ $laporanBaruCount }}
                                            <span class="visually-hidden">laporan baru</span>
                                        </span>
                                    @endif
                                </a>

                                <!-- Menu Dropdown Notifikasi -->
                                <ul class="dropdown-menu dropdown-menu-end text-small shadow dropdown-menu-notif">
                                    <li><h6 class="dropdown-header">Laporan Baru (Status: Dikirim)</h6></li>
                                    <li><hr class="dropdown-divider"></li>

                                    @forelse ($laporanBaru as $laporan)
                                        <li class="notif-item">
                                            <a class="dropdown-item" href="{{ route('admin.laporan.show', $laporan->id) }}">
                                                <div class="notif-content">
                                                    <strong>Laporan #{{ $laporan->id }}</strong> dari <strong>{{ $laporan->user->nama ?? 'Pelapor' }}</strong>
                                                    <small class="d-block text-muted">{{ $laporan->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item text-muted text-center py-3">Tidak ada laporan baru.</span></li>
                                    @endforelse

                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center fw-bold" href="{{ route('admin.dashboard', ['status' => 'dikirim']) }}">Lihat Semua Laporan 'Dikirim'</a></li>
                                </ul>
                            </div>
                        @endif

                        <!-- 2. Dropdown Profil (Kode Anda yang sudah ada) -->
                        <div class="dropdown">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <span class="d-none d-sm-inline">{{ Auth::user()->nama ?? 'Pengguna' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- AKHIR DARI KODE BARU 🔼 -->
                </div>
            </header>

            <div class="container-fluid p-4">
                {{-- Konten Halaman --}}
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script Global Confirm Delete --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                let title = form.getAttribute('data-title') || 'Yakin ingin menghapus?';
                let message = form.getAttribute('data-message') || "Data yang dihapus tidak bisa dikembalikan.";

                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
    </script>

    {{-- SweetAlert2 untuk Flash Message dinamis (Versi Perbaikan Final) --}}
    @if(session('success'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Berhasil!',
                text: "{!! addslashes(session('success')) !!}",
                icon: "{{ session('swal_icon') ?? 'success' }}", 
                confirmButtonText: 'OK'
            });
        });
      </script>
    @endif

    @if(session('error'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Oops!',
                text: "{!! addslashes(session('error')) !!}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
      </script>
    @endif

</body>
</html>

