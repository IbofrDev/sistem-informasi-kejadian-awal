<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Pelapor - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    
    @include('layouts.navigation-bootstrap')

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4">Daftar Pelapor Terdaftar</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pelapor</th>
                                <th>Jenis Kapal Utama</th>
                                <th class="text-center">Jumlah Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelapor as $user)
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->jenis_kapal }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $user->laporan_kejadian_count }} Laporan</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pelapor.show', $user) }}" class="btn btn-sm btn-primary">Lihat Laporan</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada pengguna dengan peran pelapor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
