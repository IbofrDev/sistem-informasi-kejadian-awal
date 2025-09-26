

<!-- Bagian 1: Identitas Pelapor -->
<h6 class="mb-3 border-bottom pb-2">Identitas Pelapor <span class="fst-italic">(Reporter's Identity)</span></h6>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <label for="nama_pelapor" class="form-label">Nama Pelapor <span class="text-danger">*</span></label>
        <input type="text" 
               class="form-control" 
               id="nama_pelapor" 
               name="nama_pelapor" 
               value="<?php echo e(old('nama_pelapor', $laporan->nama_pelapor ?? auth()->user()->nama)); ?>" 
               <?php if(!empty(auth()->user()->nama)): ?> readonly <?php endif; ?> 
               required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jabatan_pelapor" class="form-label">Jabatan <span class="fst-italic">(Position)</span> <span class="text-danger">*</span></label>
        <select class="form-select" id="jabatan_pelapor" name="jabatan_pelapor" required>
            <?php
                $jabatanOptions = [
                    'Master/Nakhoda',
                    'C/O (Chief Officer)',
                    '2nd Officer',
                    '3rd Officer'
                ];
                $jabatanSelected = old('jabatan_pelapor', $laporan->jabatan_pelapor ?? auth()->user()->jabatan);
            ?>
            <?php $__currentLoopData = $jabatanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($jabatan); ?>" <?php echo e($jabatanSelected == $jabatan ? 'selected' : ''); ?>>
                    <?php echo e($jabatan); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="telepon_pelapor" class="form-label">Nomor Telepon <span class="fst-italic">(Phone Number)</span> <span class="text-danger">*</span></label>
        <input type="tel" 
               class="form-control" 
               id="telepon_pelapor" 
               name="telepon_pelapor"
               value="<?php echo e(old('telepon_pelapor', $laporan->telepon_pelapor ?? auth()->user()->phone_number)); ?>" 
               required>
    </div>
</div>

<!-- Bagian 2: Identitas Kapal -->
<h6 class="mb-3 border-bottom pb-2">Identitas Kapal <span class="fst-italic">(Ship's Identity)</span></h6>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <label for="jenis_kapal" class="form-label">Jenis Kapal <span class="fst-italic">(Ship's Type)</span> <span class="text-danger">*</span></label>
        <select class="form-select" id="jenis_kapal" name="jenis_kapal" required>
            <?php
                $jenisKapalOptions = [
                    'KM (Kapal Motor)',
                    'MV (Motor Vessel)',
                    'MT (Motor Tanker)',
                    'SPOB (Self Propeller Oil Barge)',
                    'LCT (Landing Craft Tanker)',
                    'TB (TUG Boat)',
                    'BG (Barge)',
                    'FC (Floating Crane)',
                    'KLM (Kapal Layar Motor / Yacth)',
                ];
                $jenisKapalSelected = old('jenis_kapal', $laporan->jenis_kapal ?? auth()->user()->jenis_kapal);
            ?>
            <?php $__currentLoopData = $jenisKapalOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($jenis); ?>" <?php echo e($jenisKapalSelected == $jenis ? 'selected' : ''); ?>>
                    <?php echo e($jenis); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="nama_kapal" class="form-label">Nama Kapal <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_kapal" name="nama_kapal"
               value="<?php echo e(old('nama_kapal', $laporan->nama_kapal ?? '')); ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="nama_kapal_kedua" class="form-label">Nama Kapal ke-2 (opsional)</label>
        <input type="text" class="form-control" id="nama_kapal_kedua" name="nama_kapal_kedua"
               value="<?php echo e(old('nama_kapal_kedua', $laporan->nama_kapal_kedua ?? '')); ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="bendera_kapal" class="form-label">Bendera Kapal <span class="fst-italic">(Flag)</span> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="bendera_kapal" name="bendera_kapal"
               value="<?php echo e(old('bendera_kapal', $laporan->bendera_kapal ?? '')); ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="grt_kapal" class="form-label">Berat Kotor <span class="fst-italic">(GRT)</span> <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="grt_kapal" name="grt_kapal"
               value="<?php echo e(old('grt_kapal', $laporan->grt_kapal ?? '')); ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="imo_number" class="form-label">IMO Number / Tanda Selar <span class="fst-italic">(Optional)</span></label>
        <input type="text" class="form-control" id="imo_number" name="imo_number"
               value="<?php echo e(old('imo_number', $laporan->imo_number ?? '')); ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label for="pelabuhan_asal" class="form-label">Pelabuhan Asal (Last Port) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pelabuhan_asal" name="pelabuhan_asal"
               value="<?php echo e(old('pelabuhan_asal', $laporan->pelabuhan_asal ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="waktu_berangkat" class="form-label">Tgl. Berangkat <span class="fst-italic">(Time Departure)</span> <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="waktu_berangkat" name="waktu_berangkat"
               value="<?php echo e(old('waktu_berangkat', isset($laporan->waktu_berangkat) ? \Carbon\Carbon::parse($laporan->waktu_berangkat)->format('Y-m-d\TH:i') : '')); ?>"
               required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="pelabuhan_tujuan" class="form-label">Pelabuhan Tujuan (Destination) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pelabuhan_tujuan" name="pelabuhan_tujuan"
               value="<?php echo e(old('pelabuhan_tujuan', $laporan->pelabuhan_tujuan ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="estimasi_tiba" class="form-label">Estimasi Tgl. Tiba (ETA) <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="estimasi_tiba" name="estimasi_tiba"
               value="<?php echo e(old('estimasi_tiba', isset($laporan->estimasi_tiba) ? \Carbon\Carbon::parse($laporan->estimasi_tiba)->format('Y-m-d\TH:i') : '')); ?>"
               required>
    </div>
</div>

<!-- Bagian 3: Kontak & Kepemilikan -->
<h6 class="mb-3 border-bottom pb-2">Kontak & Kepemilikan</h6>
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <label for="pemilik_kapal" class="form-label">Pemilik Kapal <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pemilik_kapal" name="pemilik_kapal"
               value="<?php echo e(old('pemilik_kapal', $laporan->pemilik_kapal ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="kontak_pemilik" class="form-label">Kontak Pemilik <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="kontak_pemilik" name="kontak_pemilik"
               value="<?php echo e(old('kontak_pemilik', $laporan->kontak_pemilik ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="agen_lokal" class="form-label">Agen Lokal <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="agen_lokal" name="agen_lokal"
               value="<?php echo e(old('agen_lokal', $laporan->agen_lokal ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="kontak_agen" class="form-label">Kontak Agen Lokasl <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="kontak_agen" name="kontak_agen"
               value="<?php echo e(old('kontak_agen', $laporan->kontak_agen ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="nama_pandu" class="form-label">Nama Pandu (Optional)</label>
        <input type="text" class="form-control" id="nama_pandu" name="nama_pandu"
               value="<?php echo e(old('nama_pandu', $laporan->nama_pandu ?? '')); ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label for="nomor_register_pandu" class="form-label">Nomor Register Pandu (Optional)</label>
        <input type="text" class="form-control" id="nomor_register_pandu" name="nomor_register_pandu"
               value="<?php echo e(old('nomor_register_pandu', $laporan->nomor_register_pandu ?? '')); ?>">
    </div>
</div>

<!-- Bagian 4: Data Muatan & Penumpang -->
<h6 class="mb-3 border-bottom pb-2">Data Muatan & Penumpang</h6>
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <label for="jenis_muatan" class="form-label">Jenis Muatan <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jenis_muatan" name="jenis_muatan"
               value="<?php echo e(old('jenis_muatan', $laporan->jenis_muatan ?? '')); ?>" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jumlah_muatan" class="form-label">Jumlah Muatan <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jumlah_muatan" name="jumlah_muatan"
               value="<?php echo e(old('jumlah_muatan', $laporan->jumlah_muatan ?? '')); ?>" 
               placeholder="Contoh: 100 Ton" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jumlah_penumpang" class="form-label">Jumlah Penumpang <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="jumlah_penumpang" name="jumlah_penumpang"
               value="<?php echo e(old('jumlah_penumpang', $laporan->jumlah_penumpang ?? 0)); ?>" required>
    </div>
</div><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/laporan/partials/form-halaman-satu.blade.php ENDPATH**/ ?>