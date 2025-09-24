

<?php $__env->startSection('title', 'Detail Laporan #' . $laporan->id); ?>
<?php $__env->startSection('page-title', 'Detail Laporan Kejadian'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Laporan Kejadian</h1>
        <div>
            
           <a href="<?php echo e(route('admin.laporan.print', $laporan->id)); ?>" target="_blank" class="btn btn-success">
                <i class="bi bi-printer-fill me-1"></i> Cetak PDF
            </a>
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Informasi Laporan</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="fw-bold" style="width: 20%;">Nama Pelapor</td>
                        <td style="width: 80%;">: <?php echo e($laporan->nama_pelapor ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Telepon</td>
                        <td>: <?php echo e($laporan->telepon_pelapor ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jabatan</td>
                        <td>: <?php echo e($laporan->jabatan_pelapor ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama Kapal</td>
                        <td>: <?php echo e($laporan->nama_kapal ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jenis Kapal</td>
                        <td>: <?php echo e($laporan->jenis_kapal ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Bendera</td>
                        <td>: <?php echo e($laporan->bendera_kapal ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">GRT</td>
                        
                        <td>: <?php echo e($laporan->grt_kapal ?? 'N/A'); ?></td>
                    </tr>
                    <tr class="table-light">
                        <td class="fw-bold">Posisi Lintang</td>
                        <td>: <?php echo e($laporan->posisi_lintang ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Posisi Bujur</td>
                        <td>: <?php echo e($laporan->posisi_bujur ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Kejadian</td>
                        <td>: <?php echo e(\Carbon\Carbon::parse($laporan->tanggal_laporan)->isoFormat('D MMMM YYYY, HH:mm')); ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status</td>
                        <td>:
                            <?php if($laporan->status_laporan == 'diverifikasi'): ?>
                                <span class="badge bg-warning text-dark"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                            <?php elseif($laporan->status_laporan == 'selesai'): ?>
                                <span class="badge bg-success"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Isi Laporan</h6>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;"><?php echo e($laporan->isi_laporan); ?></p>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Lampiran</h6>
        </div>
        <div class="card-body">
            <?php if($laporan->lampiran && $laporan->lampiran->count() > 0): ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $laporan->lampiran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <?php if($file->tipe_file == 'foto'): ?>
                                <a href="<?php echo e(asset('storage/' . $file->path_file)); ?>" target="_blank">
                                    <img src="<?php echo e(asset('storage/' . $file->path_file)); ?>" class="img-fluid rounded" alt="Lampiran Foto" style="height: 180px; width: 100%; object-fit: cover;">
                                </a>
                            <?php elseif($file->tipe_file == 'video'): ?>
                                <video controls class="img-fluid rounded" style="height: 180px; width: 100%; object-fit: cover;">
                                    <source src="<?php echo e(asset('storage/' . $file->path_file)); ?>" type="<?php echo e(mime_content_type(storage_path('app/public/' . $file->path_file))); ?>">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">Tidak ada lampiran yang diunggah.</p>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/admin/detail.blade.php ENDPATH**/ ?>