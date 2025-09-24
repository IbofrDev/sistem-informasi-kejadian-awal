<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Edit Laporan Kejadian - <?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Leaflet.js (untuk Peta) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Vite (untuk Bootstrap & JS Aplikasi) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/scss/app.scss', 'resources/js/app.js']); ?>
</head>
<body class="bg-light">
    
    <?php echo $__env->make('layouts.navigation-bootstrap', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4">Edit Laporan Kejadian Awal</h2>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        
                        <form action="<?php echo e(route('laporan.update', $laporan)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Bagian 1: Identitas Pelapor -->
                            <h5 class="mb-3 border-bottom pb-2">Identitas Pelapor <span class="fst-italic">(Reporter's Identity)</span></h5>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <label for="nama_pelapor" class="form-label">Nama Pelapor <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor" value="<?php echo e(old('nama_pelapor', $laporan->nama_pelapor)); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="jabatan_pelapor" class="form-label">Jabatan <span class="fst-italic">(Position)</span> <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jabatan_pelapor" name="jabatan_pelapor" required>
                                        <option value="Master/Nakhoda" <?php echo e(old('jabatan_pelapor', $laporan->jabatan_pelapor) == 'Master/Nakhoda' ? 'selected' : ''); ?>>Master/Nakhoda</option>
                                        <option value="C/O (Chief Officer)" <?php echo e(old('jabatan_pelapor', $laporan->jabatan_pelapor) == 'C/O (Chief Officer)' ? 'selected' : ''); ?>>C/O (Chief Officer)</option>
                                        <option value="2nd Officer" <?php echo e(old('jabatan_pelapor', $laporan->jabatan_pelapor) == '2nd Officer' ? 'selected' : ''); ?>>2nd Officer</option>
                                        <option value="3rd Officer" <?php echo e(old('jabatan_pelapor', $laporan->jabatan_pelapor) == '3rd Officer' ? 'selected' : ''); ?>>3rd Officer</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="telepon_pelapor" class="form-label">Nomor Telepon <span class="fst-italic">(Phone Number)</span> <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="telepon_pelapor" name="telepon_pelapor" value="<?php echo e(old('telepon_pelapor', $laporan->telepon_pelapor)); ?>" required>
                                </div>
                            </div>

                            <!-- Bagian 2: Identitas Kapal -->
                            <h5 class="mb-3 border-bottom pb-2">Identitas Kapal <span class="fst-italic">(Ship's Identity)</span></h5>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <label for="jenis_kapal" class="form-label">Jenis Kapal <span class="fst-italic">(Ship's Type)</span> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenis_kapal" name="jenis_kapal" value="<?php echo e(old('jenis_kapal', $laporan->jenis_kapal)); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nama_kapal" class="form-label">Nama Kapal <span class="fst-italic">(Ship's Name)</span> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_kapal" name="nama_kapal" value="<?php echo e(old('nama_kapal', $laporan->nama_kapal)); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bendera_kapal" class="form-label">Bendera Kapal <span class="fst-italic">(Flag Nationality)</span> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bendera_kapal" name="bendera_kapal" value="<?php echo e(old('bendera_kapal', $laporan->bendera_kapal)); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="grt_kapal" class="form-label">Berat Kotor <span class="fst-italic">(GRT)</span> <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="grt_kapal" name="grt_kapal" value="<?php echo e(old('grt_kapal', $laporan->grt_kapal)); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="imo_number" class="form-label">IMO Number / Tanda Selar <span class="fst-italic">(Optional)</span></label>
                                    <input type="text" id="imo_number" name="imo_number" class="form-control" value="<?php echo e(old('imo_number', $laporan->imo_number)); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pelabuhan_asal" class="form-label">Pelabuhan Asal <span class="fst-italic">(Last Port)</span> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pelabuhan_asal" name="pelabuhan_asal" value="<?php echo e(old('pelabuhan_asal', $laporan->pelabuhan_asal)); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="waktu_berangkat" class="form-label">Tgl. Berangkat <span class="fst-italic">(Time Departure)</span> <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="waktu_berangkat" name="waktu_berangkat" value="<?php echo e(old('waktu_berangkat', \Carbon\Carbon::parse($laporan->waktu_berangkat)->format('Y-m-d\TH:i'))); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pelabuhan_tujuan" class="form-label">Pelabuhan Tujuan <span class="fst-italic">(Port of Destination)</span> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pelabuhan_tujuan" name="pelabuhan_tujuan" value="<?php echo e(old('pelabuhan_tujuan', $laporan->pelabuhan_tujuan)); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="estimasi_tiba" class="form-label">Estimasi Tgl. Tiba <span class="fst-italic">(Est. Time Arrival)</span> <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="estimasi_tiba" name="estimasi_tiba" value="<?php echo e(old('estimasi_tiba', \Carbon\Carbon::parse($laporan->estimasi_tiba)->format('Y-m-d\TH:i'))); ?>" required>
                                </div>
                            </div>
                            
                            <!-- Bagian Posisi Kejadian -->
                            <h5 class="mb-3 border-bottom pb-2">Posisi Kejadian</h5>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="posisi_lintang" class="form-label">Latitude (Lintang) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="posisi_lintang" name="posisi_lintang" value="<?php echo e(old('posisi_lintang', $laporan->posisi_lintang)); ?>" placeholder="Contoh: 3°18'59.1&quot;S" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="posisi_bujur" class="form-label">Longitude (Bujur) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="posisi_bujur" name="posisi_bujur" value="<?php echo e(old('posisi_bujur', $laporan->posisi_bujur)); ?>" placeholder="Contoh: 114°35'24.2&quot;E" required>
                                </div>
                                <div class="col-12">
                                    <div id="map" style="height: 400px;" class="w-100 rounded"></div>
                                </div>
                            </div>

                            <!-- Bagian Isi Laporan -->
                            <h5 class="mb-3 border-bottom pb-2">Isi Laporan & Lampiran</h5>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_laporan" class="form-label">Tanggal & Waktu Kejadian <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="tanggal_laporan" name="tanggal_laporan" value="<?php echo e(old('tanggal_laporan', \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('Y-m-d\TH:i'))); ?>" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="isi_laporan" class="form-label">Isi Laporan Kejadian <span class="fst-italic">(Incident Report)</span> <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="8" required><?php echo e(old('isi_laporan', $laporan->isi_laporan)); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latInput = document.getElementById('posisi_lintang');
            const lonInput = document.getElementById('posisi_bujur');

            function dmsToDecimal(dmsString) {
                const regex = /(\d{1,3})[°|d|D]\s*(\d{1,2})['|m|M]\s*([\d\.]*)["|s|S]?\s*([NSEW])/i;
                const parts = dmsString.replace(/ /g, '').match(regex);
                if (!parts) return null;

                const degrees = parseFloat(parts[1]);
                const minutes = parseFloat(parts[2]);
                const seconds = parts[3] ? parseFloat(parts[3]) : 0;
                const direction = parts[4].toUpperCase();
                let decimal = degrees + (minutes / 60) + (seconds / 3600);
                if (direction === 'S' || direction === 'W') {
                    decimal *= -1;
                }
                return decimal;
            }

            const initialLat = dmsToDecimal(latInput.value) || -3.317;
            const initialLon = dmsToDecimal(lonInput.value) || 114.590;

            const map = L.map('map').setView([initialLat, initialLon], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap contributors' }).addTo(map);
            let marker = L.marker([initialLat, initialLon]).addTo(map);

            function decimalToDMS(decimal, isLng) {
                const dir = isLng ? (decimal >= 0 ? 'E' : 'W') : (decimal >= 0 ? 'N' : 'S');
                const absDecimal = Math.abs(decimal);
                const degrees = Math.floor(absDecimal);
                const minutesFloat = (absDecimal - degrees) * 60;
                const minutes = Math.floor(minutesFloat);
                const seconds = ((minutesFloat - minutes) * 60).toFixed(1);
                return `${degrees}°${minutes}'${seconds}"${dir}`;
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

            [latInput, lonInput].forEach(element => {
                element.addEventListener('change', updateMapFromInputs);
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/laporan/edit.blade.php ENDPATH**/ ?>