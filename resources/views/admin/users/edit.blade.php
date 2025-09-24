@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit User</h2>
            <small class="text-muted">Mengubah data untuk {{ $user->nama }}</small>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal & Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row">
                    {{-- Baris 1: Nama dan Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    {{-- Baris 2: Jabatan dan Jenis Kapal --}}
                    <div class="col-md-6 mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select" required>
                             <option disabled value="">Pilih Jabatan...</option>
                             <option value="Master/Nakhoda" {{ old('jabatan', $user->jabatan) == 'Master/Nakhoda' ? 'selected' : '' }}>Master/Nakhoda</option>
                             <option value="C/O (Chief Officer)" {{ old('jabatan', $user->jabatan) == 'C/O (Chief Officer)' ? 'selected' : '' }}>C/O (Chief Officer)</option>
                             <option value="2nd Officer" {{ old('jabatan', $user->jabatan) == '2nd Officer' ? 'selected' : '' }}>2nd Officer</option>
                             <option value="3rd Officer" {{ old('jabatan', $user->jabatan) == '3rd Officer' ? 'selected' : '' }}>3rd Officer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kapal" class="form-label">Jenis Kapal</label>
                        <select id="jenis_kapal" name="jenis_kapal" class="form-select" required>
                            <option disabled value="">Pilih Jenis Kapal...</option>
                            <option value="KM (Kapal Motor)" {{ old('jenis_kapal', $user->jenis_kapal) == 'KM (Kapal Motor)' ? 'selected' : '' }}>KM (Kapal Motor)</option>
                            <option value="MV (Motor Vessel)" {{ old('jenis_kapal', $user->jenis_kapal) == 'MV (Motor Vessel)' ? 'selected' : '' }}>MV (Motor Vessel)</option>
                            <option value="MT (Motor Tanker)" {{ old('jenis_kapal', $user->jenis_kapal) == 'MT (Motor Tanker)' ? 'selected' : '' }}>MT (Motor Tanker)</option>
                            <option value="SPOB (Self Propeller Oil Barge)" {{ old('jenis_kapal', $user->jenis_kapal) == 'SPOB (Self Propeller Oil Barge)' ? 'selected' : '' }}>SPOB (Self Propeller Oil Barge)</option>
                            <option value="LCT (Landing Craft Tanker)" {{ old('jenis_kapal', $user->jenis_kapal) == 'LCT (Landing Craft Tanker)' ? 'selected' : '' }}>LCT (Landing Craft Tanker)</option>
                            <option value="TB (TUG Boat)" {{ old('jenis_kapal', $user->jenis_kapal) == 'TB (TUG Boat)' ? 'selected' : '' }}>TB (TUG Boat)</option>
                            <option value="BG (Barge)" {{ old('jenis_kapal', $user->jenis_kapal) == 'BG (Barge)' ? 'selected' : '' }}>BG (Barge)</option>
                            <option value="FC (Floating Crane)" {{ old('jenis_kapal', $user->jenis_kapal) == 'FC (Floating Crane)' ? 'selected' : '' }}>FC (Floating Crane)</option>
                            <option value="KLM (Kapal Layar Motor / Yacth)" {{ old('jenis_kapal', $user->jenis_kapal) == 'KLM (Kapal Layar Motor / Yacth)' ? 'selected' : '' }}>KLM (Kapal Layar Motor / Yacth)</option>
                        </select>
                    </div>

                    {{-- Baris 3: Role --}}
                    <div class="col-md-12 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select" required {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                            <option value="pelapor" {{ old('role', $user->role) == 'pelapor' ? 'selected' : '' }}>Pelapor</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @if(auth()->id() == $user->id)
                            <small class="text-muted">Anda tidak dapat mengubah role akun Anda sendiri.</small>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                    </div>

                    <hr class="my-3">
                    <h5 class="mb-3">Ubah Password (Opsional)</h5>

                    {{-- Baris 4: Password Baru & Konfirmasi --}}
                     <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
