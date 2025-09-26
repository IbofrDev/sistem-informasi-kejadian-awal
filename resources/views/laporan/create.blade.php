@extends('layouts.app')

@section('title', 'Buat Laporan Kejadian')
@section('page-title', 'Form Laporan Kejadian Awal')

@section('content')

    {{-- Leaflet.js untuk peta --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            {{-- Stepper indikator --}}
            <div class="d-flex justify-content-around text-center" id="stepper">

                {{-- STEP 1 --}}
                <div class="w-100" id="stepper-1">
                    <h5 class="fw-bold mb-1">
                        <span class="badge bg-warning rounded-pill me-2">1</span>
                        <span class="step-label text-dark">Data Kapal</span>
                    </h5>
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar bg-warning" style="width:100%;"></div>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="w-100 mx-3" id="stepper-2">
                    <h5 class="fw-bold mb-1">
                        <span class="badge bg-secondary rounded-pill me-2">2</span>
                        <span class="step-label text-muted">Posisi</span>
                    </h5>
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar bg-secondary" style="width:0%;"></div>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="w-100" id="stepper-3">
                    <h5 class="fw-bold mb-1">
                        <span class="badge bg-secondary rounded-pill me-2">3</span>
                        <span class="step-label text-muted">Laporan</span>
                    </h5>
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar bg-secondary" style="width:0%;"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" id="report-form">
                @csrf

                {{-- STEP 1 --}}
                <div class="form-step active" id="step-1">
                    @include('laporan.partials.form-halaman-satu')
                </div>

                {{-- STEP 2 --}}
                <div class="form-step" id="step-2" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h5 class="mb-0 fw-bold">Posisi Kejadian</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-get-location">
                            <i class="bi bi-geo-alt-fill me-1"></i> Gunakan Lokasi Saat Ini
                        </button>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="posisi_lintang" class="form-label">Latitude (Lintang) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="posisi_lintang" name="posisi_lintang" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="posisi_bujur" class="form-label">Longitude (Bujur) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="posisi_bujur" name="posisi_bujur" required>
                        </div>
                        <div class="col-12">
                            <p class="form-text">Klik pada peta untuk mengisi koordinat, atau isi manual.</p>
                            <div id="map" style="height:400px;" class="w-100 rounded border shadow-sm"></div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="form-step" id="step-3" style="display: none;">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_laporan" class="form-label">Tanggal & Waktu Kejadian <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="tanggal_laporan" name="tanggal_laporan" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="isi_laporan" class="form-label">Isi Laporan Kejadian <span class="fst-italic">(Incident Report)</span> <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="8" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="lampiran" class="form-label">Lampiran (Photo/Video)</label>
                            <input class="form-control" type="file" id="lampiran" name="lampiran[]" multiple accept="image/*,video/*">
                        </div>
                        <div class="col-12 mt-3">
                            <p class="form-text">Laporan ini akan disubmit atas nama: <strong id="nama-pelapor-konfirmasi"></strong> (<span id="jabatan-pelapor-konfirmasi"></span>)</p>
                        </div>
                    </div>
                </div>

                {{-- Tombol navigasi --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="btn-prev" style="display: none;">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </button>
                    <button type="button" class="btn btn-warning" id="btn-next">
                        Lanjut <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="btn-submit" style="display: none;">
                        <i class="bi bi-check-circle-fill me-2"></i> Submit Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- LOGIKA MULTI STEP ---
        let currentStep = 1;
        const steps = document.querySelectorAll('.form-step');
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');

        function updateStepper(stepNumber) {
            for (let i = 1; i <= steps.length; i++) {
                const stepElem = document.getElementById(`stepper-${i}`); // ✅ pakai backtick
                const badge = stepElem.querySelector('.badge');
                const progressBar = stepElem.querySelector('.progress-bar');
                const label = stepElem.querySelector('.step-label');

                if (i <= stepNumber) {
                    badge.classList.remove('bg-secondary'); badge.classList.add('bg-warning');
                    progressBar.classList.remove('bg-secondary'); progressBar.classList.add('bg-warning');
                    label.classList.remove('text-muted'); label.classList.add('text-dark');
                } else {
                    badge.classList.remove('bg-warning'); badge.classList.add('bg-secondary');
                    progressBar.classList.remove('bg-warning'); progressBar.classList.add('bg-secondary');
                    label.classList.remove('text-dark'); label.classList.add('text-muted');
                }

                // Proporsi progress
                if (i < stepNumber) progressBar.style.width = '100%';
                else if (i === stepNumber) progressBar.style.width = '50%';
                else progressBar.style.width = '0%';
            }
        }

        function showStep(stepNumber) {
            steps.forEach((step, index) => {
                step.style.display = (index + 1 === stepNumber) ? 'block' : 'none';
            });
            btnPrev.style.display = (stepNumber > 1) ? 'inline-block' : 'none';
            btnNext.style.display = (stepNumber < steps.length) ? 'inline-block' : 'none';
            btnSubmit.style.display = (stepNumber === steps.length) ? 'inline-block' : 'none';

            updateStepper(stepNumber);

            if (stepNumber === 2 && !map) { initializeMap(); }
            if (stepNumber === 3) {
                const namaPelapor = document.getElementById('nama_pelapor');
                const jabatanPelapor = document.getElementById('jabatan_pelapor');
                if (namaPelapor) document.getElementById('nama-pelapor-konfirmasi').textContent = namaPelapor.value;
                if (jabatanPelapor) document.getElementById('jabatan-pelapor-konfirmasi').textContent = jabatanPelapor.value;
            }
        }

        btnNext.addEventListener('click', () => {
            if (currentStep < steps.length) { currentStep++; showStep(currentStep); window.scrollTo(0,0); }
        });
        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) { currentStep--; showStep(currentStep); window.scrollTo(0,0); }
        });

        // Inisialisasi step pertama
        showStep(currentStep);

        // --- LEAFLET PETA ---
        let map;
        function initializeMap() {
            const latInput = document.getElementById('posisi_lintang');
            const lonInput = document.getElementById('posisi_bujur');
            const btnGetLocation = document.getElementById('btn-get-location');

            map = L.map('map').setView([-3.317, 114.590], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            let marker = L.marker([-3.317, 114.590]).addTo(map);

            function decimalToDMS(decimal, isLng) {
                const dir = isLng ? (decimal >= 0 ? 'E' : 'W') : (decimal >= 0 ? 'N' : 'S');
                const absDecimal = Math.abs(decimal);
                const degrees = Math.floor(absDecimal);
                const minutesFloat = (absDecimal - degrees) * 60;
                const minutes = Math.floor(minutesFloat);
                const seconds = ((minutesFloat - minutes) * 60).toFixed(1);
                return `${degrees}°${minutes}'${seconds}"${dir}`; // ✅ pakai backtick
            }

            function dmsToDecimal(dmsString) {
                if (!dmsString) return null;
                const regex = /(\d{1,3})[°|d|D]\s*(\d{1,2})['|m|M]\s*([\d\.]*)["|s|S]?\s*([NSEW])/i; // ✅ fixed
                const parts = dmsString.replace(/ /g, '').match(regex);
                if (!parts) return null;
                const degrees = parseFloat(parts[1]);
                const minutes = parseFloat(parts[2]);
                const seconds = parts[3] ? parseFloat(parts[3]) : 0;
                const direction = parts[4].toUpperCase();
                let decimal = degrees + (minutes/60) + (seconds/3600);
                if (direction === 'S' || direction === 'W') decimal *= -1;
                return decimal;
            }

            function updateMapFromInputs() {
                const latDecimal = dmsToDecimal(latInput.value);
                const lonDecimal = dmsToDecimal(lonInput.value);
                if (latDecimal !== null && lonDecimal !== null) {
                    const newLatLng = [latDecimal, lonDecimal];
                    marker.setLatLng(newLatLng);
                    map.panTo(newLatLng);
                }
            }
            function updateInputsFromMap(lat, lng) {
                latInput.value = decimalToDMS(lat, false);
                lonInput.value = decimalToDMS(lng, true);
            }

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                updateInputsFromMap(lat, lng);
                marker.setLatLng([lat, lng]);
            });

            btnGetLocation.addEventListener('click', function() {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const { latitude, longitude } = position.coords;
                    updateInputsFromMap(latitude, longitude);
                    marker.setLatLng([latitude, longitude]);
                    map.setView([latitude, longitude], 16);
                }, () => alert('Gagal mendapatkan lokasi.'));
            });

            [latInput, lonInput].forEach(el => el.addEventListener('change', updateMapFromInputs));
        }

        // --- Tampilkan field Kapal Kedua jika Tugboat ---
        const jenisKapalSelect = document.getElementById('jenis_kapal');
        const kapalKeduaWrapper = document.getElementById('kapal-kedua-wrapper');
        if (jenisKapalSelect && kapalKeduaWrapper) {
            jenisKapalSelect.addEventListener('change', function() {
                kapalKeduaWrapper.style.display = (this.value === 'TB (TUG Boat)') ? 'block' : 'none';
            });
            kapalKeduaWrapper.style.display = (jenisKapalSelect.value === 'TB (TUG Boat)') ? 'block' : 'none';
        }
    });
    </script>
    @endpush

@endsection