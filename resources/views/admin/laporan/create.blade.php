@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Laporan Baru (Admin)</h1>

    <form action="{{ route('admin.laporan.store') }}" method="POST" enctype="multipart/form-data" id="report-form">
        @csrf

        <!-- Stepper -->
        <div class="stepper">
            <div class="step" id="step-1">
                <h3>Data Kapal</h3>
                {{-- Sama seperti pelapor, include partial --}}
                @include('laporan.partials.form-halaman-satu')

                <button type="button" class="btn btn-primary next-step">Next</button>
            </div>

            <div class="step d-none" id="step-2">
                <h3>Posisi Kejadian</h3>
                <div class="mb-3">
                    <label for="posisi_lintang" class="form-label">Posisi Lintang</label>
                    <input type="text" name="posisi_lintang" id="posisi_lintang" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="posisi_bujur" class="form-label">Posisi Bujur</label>
                    <input type="text" name="posisi_bujur" id="posisi_bujur" class="form-control" required>
                </div>

                {{-- Map (Leaflet) --}}
                <div id="map" style="height: 400px;"></div>

                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="button" class="btn btn-primary next-step">Next</button>
            </div>

            <div class="step d-none" id="step-3">
                <h3>Isi Laporan</h3>
                <div class="mb-3">
                    <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
                    <input type="date" name="tanggal_laporan" id="tanggal_laporan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="isi_laporan" class="form-label">Isi Laporan</label>
                    <textarea name="isi_laporan" id="isi_laporan" class="form-control" rows="5" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran (opsional)</label>
                    <input type="file" name="lampiran[]" id="lampiran" class="form-control" multiple>
                </div>

                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                <button type="submit" class="btn btn-success">Kirim Laporan</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    {{-- Script stepper --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const steps = document.querySelectorAll(".step");
            let currentStep = 0;

            function showStep(index) {
                steps.forEach((step, i) => {
                    step.classList.toggle("d-none", i !== index);
                });
            }

            document.querySelectorAll(".next-step").forEach(btn => {
                btn.addEventListener("click", () => {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll(".prev-step").forEach(btn => {
                btn.addEventListener("click", () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            showStep(currentStep);
        });
    </script>

    {{-- Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0, 120], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            document.getElementById('posisi_lintang').value = lat;
            document.getElementById('posisi_bujur').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    </script>
@endpush
