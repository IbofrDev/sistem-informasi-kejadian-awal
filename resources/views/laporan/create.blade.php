@extends('layouts.app')

@section('title', 'Buat Laporan Kejadian')
@section('page-title', 'Form Laporan Kejadian Awal')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            {{-- INDIKATOR PROGRES / STEPPER --}}
            <div class="d-flex justify-content-around text-center" id="stepper">
                <div class="w-100" id="stepper-1">
                    <h5 class="fw-bold text-primary mb-1">
                        <span class="badge bg-primary rounded-pill me-2">1</span> Data Kapal
                    </h5>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%;"></div>
                    </div>
                </div>
                <div class="w-100 mx-3" id="stepper-2">
                    <h5 class="fw-bold text-muted mb-1">
                        <span class="badge bg-secondary rounded-pill me-2">2</span> Posisi
                    </h5>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="w-100" id="stepper-3">
                    <h5 class="fw-bold text-muted mb-1">
                        <span class="badge bg-secondary rounded-pill me-2">3</span> Laporan
                    </h5>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" id="report-form">
                @csrf

                <div class="form-step active" id="step-1">
                    @include('laporan.partials.form-halaman-satu')
                </div>

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
                            <input type="text" class="form-control" id="posisi_lintang" name="posisi_lintang" placeholder="Contoh: 3°18'59.1&quot;S" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="posisi_bujur" class="form-label">Longitude (Bujur) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="posisi_bujur" name="posisi_bujur" placeholder="Contoh: 114°35'24.2&quot;E" required>
                        </div>
                        <div class="col-12">
                            <p class="form-text">Klik pada peta untuk mengisi koordinat, atau isi manual.</p>
                            <div id="map" style="height: 400px;" class="w-100 rounded border shadow-sm"></div>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step-3" style="display: none;">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_laporan" class="form-label">Tanggal & Waktu Kejadian <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="tanggal_laporan" name="tanggal_laporan" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="isi_laporan" class="form-label">Isi Laporan Kejadian <span class="fst-italic">(Incident Report)</span> <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="8" required placeholder="Jelaskan kronologi kejadian..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="lampiran" class="form-label">Lampiran <span class="fst-italic">(Photo/Video)</span></label>
                            <input class="form-control" type="file" id="lampiran" name="lampiran[]" multiple accept="image/*,video/*">
                            <div class="form-text">Anda bisa memilih lebih dari satu file.</div>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="form-text">Laporan ini akan disubmit atas nama: <strong id="nama-pelapor-konfirmasi"></strong> (<span id="jabatan-pelapor-konfirmasi"></span>)</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="btn-prev" style="display: none;"><i class="bi bi-arrow-left me-2"></i> Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn-next">Lanjut <i class="bi bi-arrow-right ms-2"></i></button>
                    <button type="submit" class="btn btn-success" id="btn-submit" style="display: none;"><i class="bi bi-check-circle-fill me-2"></i> Submit Laporan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script dipindahkan ke dalam @push untuk best practice --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- DEKLARASI VARIABEL GLOBAL ---
            let map; 
            let mapInitialized = false; 

            // --- LOGIKA MULTI-STEP FORM ---
            let currentStep = 1;
            const steps = document.querySelectorAll('.form-step');
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');
            const stepper = document.getElementById('stepper');

            function updateStepper(stepNumber) {
                for (let i = 1; i <= steps.length; i++) {
                    const stepElem = document.getElementById(`stepper-${i}`);
                    const title = stepElem.querySelector('h5');
                    const badge = title.querySelector('.badge');
                    const progressBar = stepElem.querySelector('.progress-bar');

                    if (i < stepNumber) { // Langkah yang sudah dilewati
                        title.classList.remove('text-muted');
                        title.classList.add('text-primary');
                        badge.classList.remove('bg-secondary');
                        badge.classList.add('bg-primary');
                        progressBar.classList.remove('bg-secondary');
                        progressBar.classList.add('bg-primary');
                        progressBar.style.width = '100%';
                    } else if (i === stepNumber) { // Langkah saat ini
                        title.classList.remove('text-muted');
                        title.classList.add('text-primary');
                        badge.classList.remove('bg-secondary');
                        badge.classList.add('bg-primary');
                        progressBar.classList.remove('bg-secondary');
                        progressBar.classList.add('bg-primary');
                        progressBar.style.width = '50%'; // Atau 100% jika mau langsung penuh
                    } else { // Langkah berikutnya
                        title.classList.remove('text-primary');
                        title.classList.add('text-muted');
                        badge.classList.remove('bg-primary');
                        badge.classList.add('bg-secondary');
                        progressBar.classList.remove('bg-primary');
                        progressBar.classList.add('bg-secondary');
                        progressBar.style.width = '0%';
                    }
                }
            }
            
            function showStep(stepNumber) {
                steps.forEach((step, index) => {
                    step.style.display = (index + 1 === stepNumber) ? 'block' : 'none';
                });
                btnPrev.style.display = (stepNumber > 1) ? 'inline-block' : 'none';
                btnNext.style.display = (stepNumber < steps.length) ? 'inline-block' : 'none';
                btnSubmit.style.display = (stepNumber === steps.length) ? 'inline-block' : 'none';
                
                // ---- KODE BARU UNTUK UPDATE STEPPER ----
                updateStepper(stepNumber);
                // -----------------------------------------

                if (stepNumber === 2 && !mapInitialized) {
                    initializeMap();
                    mapInitialized = true;
                }
                if (stepNumber === 3) {
                    const namaPelapor = document.getElementById('nama_pelapor');
                    const jabatanPelapor = document.getElementById('jabatan_pelapor');
                    if (namaPelapor) document.getElementById('nama-pelapor-konfirmasi').textContent = namaPelapor.value;
                    if (jabatanPelapor) document.getElementById('jabatan-pelapor-konfirmasi').textContent = jabatanPelapor.value;
                }
            }

            btnNext.addEventListener('click', () => { if (currentStep < steps.length) { currentStep++; showStep(currentStep); window.scrollTo(0,0); } });
            btnPrev.addEventListener('click', () => { if (currentStep > 1) { currentStep--; showStep(currentStep); window.scrollTo(0,0); } });
            showStep(currentStep);

            // --- FUNGSI UNTUK INISIALISASI PETA LEAFLET (LOGIKA LAMA ANDA) ---
            function initializeMap() {
                const latInput = document.getElementById('posisi_lintang');
                const lonInput = document.getElementById('posisi_bujur');
                const btnGetLocation = document.getElementById('btn-get-location');
                
                // Inisialisasi peta hanya jika elemen 'map' ada
                if (document.getElementById('map')) {
                    map = L.map('map').setView([-3.317, 114.590], 10);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap contributors' }).addTo(map);
                    let marker = L.marker([-3.317, 114.590]).addTo(map);

                    function decimalToDMS(decimal, isLng) {
                        const dir = isLng ? (decimal >= 0 ? 'E' : 'W') : (decimal >= 0 ? 'N' : 'S');
                        const absDecimal = Math.abs(decimal);
                        const degrees = Math.floor(absDecimal);
                        const minutesFloat = (absDecimal - degrees) * 60;
                        const minutes = Math.floor(minutesFloat);
                        const seconds = ((minutesFloat - minutes) * 60).toFixed(1);
                        return `${degrees}°${minutes}'${seconds}"${dir}`;
                    }

                    function dmsToDecimal(dmsString) {
                        if (!dmsString) return null;
                        const regex = /(\d{1,3})[°|d|D]\s*(\d{1,2})['|m|M]\s*([\d\.]*)["|s|S]?\s*([NSEW])/i;
                        const parts = dmsString.replace(/ /g, '').match(regex);
                        if (!parts) return null;
                        const degrees = parseFloat(parts[1]);
                        const minutes = parseFloat(parts[2]);
                        const seconds = parts[3] ? parseFloat(parts[3]) : 0;
                        const direction = parts[4].toUpperCase();
                        let decimal = degrees + (minutes / 60) + (seconds / 3600);
                        if (direction === 'S' || direction === 'W') { decimal *= -1; }
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
                        }, () => alert('Gagal mendapatkan lokasi. Pastikan Anda mengizinkan akses lokasi.'));
                    });

                    [latInput, lonInput].forEach(element => { element.addEventListener('change', updateMapFromInputs); });
                }
            }
            
            // --- LOGIKA TUG BOAT (LOGIKA LAMA ANDA) ---
            const jenisKapalSelect = document.getElementById('jenis_kapal');
            const kapalKeduaWrapper = document.getElementById('kapal-kedua-wrapper');
            if (jenisKapalSelect && kapalKeduaWrapper) {
                jenisKapalSelect.addEventListener('change', function() {
                    kapalKeduaWrapper.style.display = (this.value === 'TB (TUG Boat)') ? 'block' : 'none';
                });
                // Initial check in case the form is pre-filled
                kapalKeduaWrapper.style.display = (jenisKapalSelect.value === 'TB (TUG Boat)') ? 'block' : 'none';
            }
        });
    </script>
    @endpush
@endsection