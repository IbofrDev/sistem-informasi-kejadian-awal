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

    {{-- Alpine.js (jika belum di-include global via app.js) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            {{-- Menampilkan error validasi global --}}
             @if ($errors->any())
                 <div class="alert alert-danger mb-4">
                     <h5 class="alert-heading">Terjadi Kesalahan Validasi!</h5>
                     <ul class="mb-0">
                         @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
             @endif

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
                            <input type="text" class="form-control @error('posisi_lintang') is-invalid @enderror"
                                   id="posisi_lintang" name="posisi_lintang"
                                   value="{{ old('posisi_lintang') }}" required>
                            @error('posisi_lintang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="posisi_bujur" class="form-label">Longitude (Bujur) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('posisi_bujur') is-invalid @enderror"
                                   id="posisi_bujur" name="posisi_bujur"
                                   value="{{ old('posisi_bujur') }}" required>
                             @error('posisi_bujur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <p class="form-text">Klik pada peta untuk mengisi koordinat, atau isi manual.</p>
                            <div id="map" style="height:400px;" class="w-100 rounded border shadow-sm"></div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="form-step" id="step-3" style="display: none;"
                     x-data="{ selectedJenisKecelakaan: '{{ old('jenis_kecelakaan') }}' }">
                    
                    {{-- 🔽 PERUBAHAN LAYOUT DIMULAI DARI SINI 🔽 --}}
                    {{-- Baris untuk Jenis Kecelakaan & Tanggal --}}
                    <div class="row mb-3"> 
                        {{-- Kolom Kiri: Jenis Kecelakaan --}}
                        <div class="col-md-6">
                            <label for="jenis_kecelakaan" class="form-label">Jenis Kecelakaan Kapal <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kecelakaan') is-invalid @enderror"
                                    id="jenis_kecelakaan" name="jenis_kecelakaan"
                                    x-model="selectedJenisKecelakaan" 
                                    
                                    {{-- INI ADALAH MODIFIKASI YANG DITAMBAHKAN --}}
                                    @change="if (selectedJenisKecelakaan !== 'Kecelakaan Antar Kapal (Tabrakan)') {
                                        const pihakTerkaitInput = document.getElementById('pihak_terkait');
                                        if (pihakTerkaitInput) pihakTerkaitInput.value = '';
                                    }"
                                    {{-- AKHIR MODIFIKASI --}}
                                    
                                    required>
                                <option value="" disabled {{ old('jenis_kecelakaan') ? '' : 'selected' }}>-- Pilih Jenis Kecelakaan --</option>
                                
                                @php
                                    $jenisKecelakaanOptions = [
                                        'Kecelakaan Tunggal',
                                        'Kecelakaan Antar Kapal (Tabrakan)',
                                        'Kecelakaan di Pelabuhan / Saat Sandar',
                                        'Kecelakaan karena Cuaca Ekstrem / Alam',
                                        'Kapal Terbalik / Tenggelam',
                                        'Kehilangan Kendali (Mesin / Kemudi Rusak)',
                                        'Kecelakaan dengan Fasilitas / Masyarakat',
                                        'Insiden Muatan / Tumpahan Bahan Berbahaya',
                                    ];
                                @endphp
                                @foreach ($jenisKecelakaanOptions as $option)
                                    <option value="{{ $option }}" {{ old('jenis_kecelakaan') == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                             @error('jenis_kecelakaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kolom Kanan: Tanggal & Waktu --}}
                        <div class="col-md-6">
                            <label for="tanggal_laporan" class="form-label">Tanggal & Waktu Kejadian <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('tanggal_laporan') is-invalid @enderror"
                                   id="tanggal_laporan" name="tanggal_laporan"
                                   value="{{ old('tanggal_laporan') }}" required>
                             @error('tanggal_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div> 

                    {{-- Input Pihak Terkait (Muncul jika Tabrakan) --}}
                    {{-- Tetap full width (col-12) agar jelas --}}
                    <div class="row mb-3"> 
                        <div class="col-12" 
                             x-show="selectedJenisKecelakaan === 'Kecelakaan Antar Kapal (Tabrakan)'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             style="display: none;"> 

                            <label for="pihak_terkait" class="form-label">Pihak Terkait (jika tabrakan) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pihak_terkait') is-invalid @enderror"
                                   id="pihak_terkait" name="pihak_terkait"
                                   value="{{ old('pihak_terkait') }}"
                                   placeholder="Misal: Kapal Nelayan, Kapal Cargo, Kapal Penumpang, dsb.">
                             @error('pihak_terkait') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Baris untuk Isi Laporan & Lampiran --}}
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <label for="isi_laporan" class="form-label">Isi Laporan Kejadian <span class="fst-italic">(Incident Report)</span> <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('isi_laporan') is-invalid @enderror"
                                      id="isi_laporan" name="isi_laporan" rows="8"
                                      required>{{ old('isi_laporan') }}</textarea>
                             @error('isi_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="lampiran" class="form-label">Lampiran (Photo/Video)</label>
                            <input class="form-control @error('lampiran.*') is-invalid @enderror"
                                   type="file" id="lampiran" name="lampiran[]" multiple
                                   accept="image/*,video/*">
                             @error('lampiran.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             <div class="form-text">Maksimal ukuran file: 20MB per file. Format: jpg, jpeg, png, mp4, mov, avi, webm.</div>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="form-text">Laporan ini akan disubmit atas nama: <strong id="nama-pelapor-konfirmasi"></strong> (<span id="jabatan-pelapor-konfirmasi"></span>)</p>
                        </div>
                    </div>
                    {{-- 🔼 AKHIR PERUBAHAN LAYOUT 🔼 --}}
                </div>

                {{-- Tombol navigasi --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="btn-prev" style="display: none;">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </button>
                    {{-- Beri margin kiri auto agar tombol next/submit ke kanan jika prev hilang --}}
                    <button type="button" class="btn btn-warning ms-auto" id="btn-next">
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
                const stepElem = document.getElementById(`stepper-${i}`);
                if (!stepElem) continue; // Antisipasi jika ID salah
                const badge = stepElem.querySelector('.badge');
                const progressBar = stepElem.querySelector('.progress-bar');
                const label = stepElem.querySelector('.step-label');

                if (i < stepNumber) { // Step sudah dilewati
                    badge?.classList.remove('bg-secondary'); badge?.classList.add('bg-warning');
                    progressBar?.classList.remove('bg-secondary'); progressBar?.classList.add('bg-warning');
                    label?.classList.remove('text-muted'); label?.classList.add('text-dark');
                    progressBar.style.width = '100%';
                } else if (i === stepNumber) { // Step saat ini
                     badge?.classList.remove('bg-secondary'); badge?.classList.add('bg-warning');
                    progressBar?.classList.remove('bg-secondary'); progressBar?.classList.add('bg-warning');
                    label?.classList.remove('text-muted'); label?.classList.add('text-dark');
                    progressBar.style.width = '50%'; // Sedang berjalan
                } else { // Step belum dicapai
                    badge?.classList.remove('bg-warning'); badge?.classList.add('bg-secondary');
                    progressBar?.classList.remove('bg-warning'); progressBar?.classList.add('bg-secondary');
                    label?.classList.remove('text-dark'); label?.classList.add('text-muted');
                    progressBar.style.width = '0%';
                }
            }
        }

        function showStep(stepNumber) {
            steps.forEach((step, index) => {
                step.style.display = (index + 1 === stepNumber) ? 'block' : 'none';
                // Tambahkan class 'active' untuk trigger animasi jika ada
                if (index + 1 === stepNumber) step.classList.add('active');
                else step.classList.remove('active');
            });
            btnPrev.style.display = (stepNumber > 1) ? 'inline-block' : 'none';
            // Pindahkan class ms-auto dari btn-next ke btn-prev jika btn-prev tampil
            btnNext.classList.toggle('ms-auto', stepNumber === 1);

            btnNext.style.display = (stepNumber < steps.length) ? 'inline-block' : 'none';
            btnSubmit.style.display = (stepNumber === steps.length) ? 'inline-block' : 'none';

            updateStepper(stepNumber);

            // Inisialisasi peta hanya jika step 2 aktif dan peta belum dibuat
            if (stepNumber === 2 && typeof map === 'undefined') {
                initializeMap();
            }
             // Panggil invalidateSize agar peta tampil benar setelah display:block
            if (stepNumber === 2 && map) {
                setTimeout(() => map.invalidateSize(), 10);
            }

            // Update nama pelapor di konfirmasi step 3
            if (stepNumber === 3) {
                const namaPelapor = document.getElementById('nama_pelapor');
                const jabatanPelapor = document.getElementById('jabatan_pelapor');
                const namaKonfirmasi = document.getElementById('nama-pelapor-konfirmasi');
                const jabatanKonfirmasi = document.getElementById('jabatan-pelapor-konfirmasi');

                if (namaPelapor && namaKonfirmasi) namaKonfirmasi.textContent = namaPelapor.value || 'Pelapor';
                if (jabatanPelapor && jabatanKonfirmasi) jabatanKonfirmasi.textContent = jabatanPelapor.value || 'Jabatan';
            }
        }

        btnNext.addEventListener('click', () => {
             // Validasi sederhana sebelum lanjut (opsional)
             // const currentFormStep = document.getElementById(`step-${currentStep}`);
             // const inputs = currentFormStep.querySelectorAll('input[required], select[required], textarea[required]');
             // let valid = true;
             // inputs.forEach(input => { if (!input.value) valid = false; });
             // if (!valid) { alert('Harap isi semua field yang wajib (*)'); return; }

            if (currentStep < steps.length) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo(0,0); // Scroll ke atas
            }
        });
        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                window.scrollTo(0,0); // Scroll ke atas
            }
        });

        // Inisialisasi step pertama
        showStep(currentStep);

        // --- LEAFLET PETA ---
        let map; // Definisikan di scope luar agar bisa dicek
        function initializeMap() {
            const latInput = document.getElementById('posisi_lintang');
            const lonInput = document.getElementById('posisi_bujur');
            const btnGetLocation = document.getElementById('btn-get-location');

            // Koordinat default Banjarmasin/sekitar KSOP
            const defaultLat = -3.317;
            const defaultLng = 114.590;

            map = L.map('map').setView([defaultLat, defaultLng], 10); // Zoom awal 10
            L.tileLayer('https{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Marker awal
            let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // Fungsi konversi DMS dan sebaliknya (tetap sama)
            function decimalToDMS(decimal, isLng) {
                // ... (kode decimalToDMS tidak berubah) ...
                 const dir = isLng ? (decimal >= 0 ? 'E' : 'W') : (decimal >= 0 ? 'N' : 'S');
                 const absDecimal = Math.abs(decimal);
                 const degrees = Math.floor(absDecimal);
                 const minutesFloat = (absDecimal - degrees) * 60;
                 const minutes = Math.floor(minutesFloat);
                 const seconds = ((minutesFloat - minutes) * 60).toFixed(1);
                 // Pastikan formatnya konsisten untuk parsing
                 return `${degrees}° ${minutes}' ${seconds}" ${dir}`;
            }

            function dmsToDecimal(dmsString) {
                // ... (kode dmsToDecimal tidak berubah) ...
                if (!dmsString) return null;
                // Regex diperbaiki untuk lebih toleran spasi dan simbol
                const regex = /(\d{1,3})[°d\s]*(\d{1,2})['m\s]*([\d\.]*)["s\s]?\s*([NSEW])/i;
                const parts = dmsString.match(regex);
                if (!parts) return null; // Tidak cocok format

                const degrees = parseFloat(parts[1]);
                const minutes = parseFloat(parts[2]);
                const seconds = parts[3] ? parseFloat(parts[3]) : 0;
                const direction = parts[4].toUpperCase();

                let decimal = degrees + (minutes / 60) + (seconds / 3600);

                // Pembulatan ke 6 angka desimal (presisi standar GPS)
                decimal = parseFloat(decimal.toFixed(6));

                if (direction === 'S' || direction === 'W') {
                    decimal *= -1;
                }
                return decimal;
            }

            // Update map ketika input berubah
            function updateMapFromInputs() {
                const latDecimal = dmsToDecimal(latInput.value);
                const lonDecimal = dmsToDecimal(lonInput.value);
                if (latDecimal !== null && lonDecimal !== null && !isNaN(latDecimal) && !isNaN(lonDecimal)) {
                     // Batasi nilai latitude dan longitude
                    const validLat = Math.max(-90, Math.min(90, latDecimal));
                    const validLng = Math.max(-180, Math.min(180, lonDecimal));

                    if (validLat !== latDecimal || validLng !== lonDecimal) {
                         console.warn("Koordinat di luar batas, disesuaikan.");
                         updateInputsFromMap(validLat, validLng); // Update input jika disesuaikan
                    }

                    const newLatLng = [validLat, validLng];
                    marker.setLatLng(newLatLng);
                    map.panTo(newLatLng);
                } else {
                    console.warn("Format DMS tidak valid.");
                }
            }
             // Update input ketika marker digeser atau map diklik
             function updateInputsFromMap(lat, lng) {
                 // Pembulatan sebelum konversi ke DMS
                 const roundedLat = parseFloat(lat.toFixed(6));
                 const roundedLng = parseFloat(lng.toFixed(6));
                 latInput.value = decimalToDMS(roundedLat, false);
                 lonInput.value = decimalToDMS(roundedLng, true);
             }

            // Event listener klik map
            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                updateInputsFromMap(lat, lng);
                marker.setLatLng([lat, lng]);
            });

             // Event listener drag marker
             marker.on('dragend', function(e) {
                 const { lat, lng } = e.target.getLatLng();
                  updateInputsFromMap(lat, lng);
             });

            // Event listener tombol lokasi saat ini
            btnGetLocation.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                    return;
                }
                navigator.geolocation.getCurrentPosition(function(position) {
                    const { latitude, longitude } = position.coords;
                    updateInputsFromMap(latitude, longitude);
                    const newLatLng = [latitude, longitude];
                    marker.setLatLng(newLatLng);
                    map.setView(newLatLng, 16); // Zoom lebih dekat saat pakai lokasi
                }, () => {
                     // Error handling lebih baik
                     Swal.fire({
                         icon: 'error',
                         title: 'Gagal Mendapatkan Lokasi',
                         text: 'Pastikan Anda mengizinkan akses lokasi di browser Anda.',
                     });
                 });
            });

            // Event listener perubahan input manual
            latInput.addEventListener('change', updateMapFromInputs);
            lonInput.addEventListener('change', updateMapFromInputs);

            // Inisialisasi input jika ada old value
            if(latInput.value && lonInput.value) {
                updateMapFromInputs();
            } else {
                 // Set nilai awal input jika kosong
                 updateInputsFromMap(defaultLat, defaultLng);
            }
        }

        // --- Tampilkan field Kapal Kedua jika Tugboat ---
        const jenisKapalSelect = document.getElementById('jenis_kapal');
        const kapalKeduaWrapper = document.getElementById('kapal-kedua-wrapper');
        if (jenisKapalSelect && kapalKeduaWrapper) {
            jenisKapalSelect.addEventListener('change', function() {
                kapalKeduaWrapper.style.display = (this.value === 'TB (TUG Boat)') ? 'block' : 'none';
                // Kosongkan nilai jika bukan Tugboat
                if (this.value !== 'TB (TUG Boat)') {
                    const inputKapalKedua = kapalKeduaWrapper.querySelector('input');
                    if(inputKapalKedua) inputKapalKedua.value = '';
                }
            });
            // Cek kondisi awal saat halaman load (jika ada old value)
            kapalKeduaWrapper.style.display = (jenisKapalSelect.value === 'TB (TUG Boat)') ? 'block' : 'none';
        }
    });
    </script>
    @endpush

@endsection