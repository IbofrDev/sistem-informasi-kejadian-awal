


<?php $__env->startSection('title', 'Edit Laporan Kejadian'); ?>
<?php $__env->startSection('page-title', 'Edit Laporan Kejadian Awal'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?php echo e(route('laporan.update', $laporan)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <?php echo $__env->make('laporan.partials.form-halaman-satu', ['laporan' => $laporan], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <h6 class="mb-3">Posisi Kejadian</h6>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="posisi_lintang" class="form-label">Latitude (Lintang) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="posisi_lintang" name="posisi_lintang" 
                           value="<?php echo e(old('posisi_lintang', $laporan->posisi_lintang)); ?>" 
                           placeholder="Contoh: 3°18'59.1&quot;S" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="posisi_bujur" class="form-label">Longitude (Bujur) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="posisi_bujur" name="posisi_bujur" 
                           value="<?php echo e(old('posisi_bujur', $laporan->posisi_bujur)); ?>" 
                           placeholder="Contoh: 114°35'24.2&quot;E" required>
                </div>
                <div class="col-12">
                    <div id="map" style="height: 400px;" class="w-100 rounded"></div>
                </div>
            </div>

            
            <h6 class="mb-3">Isi Laporan & Lampiran</h6>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_laporan" class="form-label">Tanggal & Waktu Kejadian <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" id="tanggal_laporan" name="tanggal_laporan" 
                           value="<?php echo e(old('tanggal_laporan', \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('Y-m-d\TH:i'))); ?>" 
                           required>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/laporan/edit.blade.php ENDPATH**/ ?>