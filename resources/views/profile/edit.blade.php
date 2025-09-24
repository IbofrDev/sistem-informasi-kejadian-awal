@extends('layouts.app')

{{-- Mengatur judul halaman yang akan tampil di tab browser --}}
@section('title', 'Edit Profil')

{{-- Mengatur judul yang akan tampil di header halaman --}}
@section('page-title', 'Profil Saya')

{{-- Ini adalah bagian utama yang akan mengisi area kosong di layout --}}
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center g-4">

            {{-- Kartu untuk Update Informasi Profil --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        {{-- Memuat form dari file partial --}}
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Kartu untuk Update Password --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        {{-- Memuat form dari file partial --}}
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Kartu untuk Hapus Akun --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        {{-- Memuat form dari file partial --}}
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
