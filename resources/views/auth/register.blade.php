<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Register</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Figtree', sans-serif;
        }

        .register-page-wrapper {
            min-height: 100vh;
            background-image: url('/images/register.png');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
        }

        .register-card {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, .15);
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 450px;
        }

        .register-card .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .register-card .form-control,
        .register-card .form-select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
        }

        .register-card .btn {
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            background-color: #211551;
            border-color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="register-page-wrapper">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="/">
                        <img src="{{ asset('images/logo_ksop.png') }}" alt="KSOP Logo" height="40"
                            class="d-inline-block me-2">
                        <div>
                            <span class="fw-bold">SIKAP</span>
                            <small class="d-block text-muted fw-normal"
                                style="font-size: 0.7rem; line-height: 1;">Sistem Informasi Kecelakaan Kapal</small>
                        </div>
                    </a>
                </div>
            </nav>
        </header>

        <main class="d-flex align-items-center justify-content-center flex-grow-1 py-3">
            <div class="register-card">
                <h2 class="text-center mb-4">Sign Up</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror"
                            name="nama" value="{{ old('nama') }}" placeholder="Full Name..." required autofocus>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 🆕 Field Nama PT -->
                    <div class="mb-2">
                        <label for="pt" class="form-label">Nama PT / Perusahaan</label>
                        <input id="pt" type="text" class="form-control @error('pt') is-invalid @enderror" name="pt"
                            value="{{ old('pt') }}" placeholder="Nama Perusahaan..." required>
                        @error('pt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select @error('jabatan') is-invalid @enderror"
                            required>
                            <option selected disabled value="">Pilih Jabatan...</option>
                            <option value="Master/Nakhoda">Master/Nakhoda</option>
                            <option value="C/O (Chief Officer)">C/O (Chief Officer)</option>
                            <option value="2nd Officer">2nd Officer</option>
                            <option value="3rd Officer">3rd Officer</option>
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="jenis_kapal" class="form-label">Jenis Kapal</label>
                        <select id="jenis_kapal" name="jenis_kapal"
                            class="form-select @error('jenis_kapal') is-invalid @enderror" required>
                            <option selected disabled value="">Pilih Jenis Kapal...</option>
                            <option value="KM (Kapal Motor)">KM (Kapal Motor)</option>
                            <option value="MV (Motor Vessel)">MV (Motor Vessel)</option>
                            <option value="MT (Motor Tanker)">MT (Motor Tanker)</option>
                            <option value="SPOB (Self Propeller Oil Barge)">SPOB (Self Propeller Oil Barge)</option>
                            <option value="LCT (Landing Craft Tanker)">LCT (Landing Craft Tanker)</option>
                            <option value="TB (TUG Boat)">TB (TUG Boat)</option>
                            <option value="BG (Barge)">BG (Barge)</option>
                            <option value="FC (Floating Crane)">FC (Floating Crane)</option>
                            <option value="KLM (Kapal Layar Motor / Yacth)">KLM (Kapal Layar Motor / Yacth)</option>
                        </select>
                        @error('jenis_kapal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input id="phone_number" type="text"
                            class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                            value="{{ old('phone_number') }}" placeholder="Nomor Telepon..." required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" placeholder="Email..." required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="Password..." required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control"
                            name="password_confirmation" placeholder="Password..." required>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>

                <p class="text-center text-muted mt-3 mb-0">
                    Sudah punya akun?
                    <a href="{{ url('/') }}">Login</a>
                </p>
            </div>
        </main>
    </div>
</body>

</html>