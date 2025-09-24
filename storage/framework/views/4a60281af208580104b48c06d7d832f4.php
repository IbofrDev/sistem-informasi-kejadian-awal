

<?php $__env->startSection('title', 'Detail Laporan'); ?>
<?php $__env->startSection('page-title', 'Detail Laporan Kejadian'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-10">

        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary">Detail Laporan Kejadian</h5>
            <div class="btn-group">
                <a href="<?php echo e(route('admin.laporan.print', $laporan)); ?>" target="_blank" 
                   class="btn btn-sm btn-success">
                    <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                </a>
                <?php if(Auth::user()->role == 'admin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-bold text-primary">
                Informasi Laporan
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <tr>
                        <th style="width: 20%">Nama Pelapor</th>
                        <td><?php echo e($laporan->nama_pelapor); ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><?php echo e($laporan->telepon_pelapor); ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?php echo e($laporan->jabatan_pelapor); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Kapal</th>
                        <td><?php echo e($laporan->nama_kapal); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kapal</th>
                        <td><?php echo e($laporan->jenis_kapal); ?></td>
                    </tr>
                    <tr>
                        <th>Bendera</th>
                        <td><?php echo e($laporan->bendera_kapal); ?></td>
                    </tr>
                    <tr>
                        <th>GRT</th>
                        <td><?php echo e($laporan->grt_kapal); ?></td>
                    </tr>
                    <tr>
                        <th>Posisi Lintang</th>
                        <td><?php echo e($laporan->posisi_lintang); ?></td>
                    </tr>
                    <tr>
                        <th>Posisi Bujur</th>
                        <td><?php echo e($laporan->posisi_bujur); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Kejadian</th>
                        <td><?php echo e(\Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y, H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light fw-bold text-primary">Isi Laporan</div>
            <div class="card-body">
                <div class="p-3 bg-light rounded"><?php echo e($laporan->isi_laporan); ?></div>
            </div>
        </div>

        
        <?php if($laporan->lampiran->isNotEmpty()): ?>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light fw-bold text-primary">Lampiran</div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $laporan->lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <?php if($file->tipe_file == 'foto'): ?>
                                    <a href="<?php echo e(asset('storage/' . $file->path_file)); ?>" target="_blank">
                                        <img src="<?php echo e(asset('storage/' . $file->path_file)); ?>" 
                                             class="card-img-top rounded-top" alt="Lampiran Foto">
                                    </a>
                                <?php else: ?>
                                    <div class="card-body text-center">
                                        <a href="<?php echo e(asset('storage/' . $file->path_file)); ?>" target="_blank" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="bi bi-camera-video me-1"></i> Lihat Video
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/laporan/show.blade.php ENDPATH**/ ?>