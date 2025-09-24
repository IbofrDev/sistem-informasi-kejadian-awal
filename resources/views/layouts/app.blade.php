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
    </style>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="d-flex">
        <nav class="sidebar vh-100 text-white p-3 shadow-sm" style="width: 280px; position: fixed; background: linear-gradient(to bottom, #28166F, #120A30);">
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
                        {{-- MODIFIKASI PADA BARIS DI BAWAH INI --}}
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <i class="bi bi-people-fill me-2"></i> <strong>Kelola User</strong>
                        </a>
                    </li>
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
            </header>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')

</body>

</html>