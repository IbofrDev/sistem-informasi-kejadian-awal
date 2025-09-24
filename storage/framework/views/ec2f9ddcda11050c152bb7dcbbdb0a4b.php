

<?php $__env->startSection('title', 'Detail User'); ?>
<?php $__env->startSection('page-title', 'Detail User'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Detail User</h2>
            <small class="text-muted">Melihat informasi dan riwayat laporan dari <?php echo e($pelapor->nama); ?>.</small>
        </div>
        <div>
            <a href="<?php echo e(route('admin.users.edit', $pelapor)); ?>" class="btn btn-sm btn-outline-primary">Edit User</a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex align-items-center">
            
            <div class="flex-shrink-0 me-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 60px; height: 60px;">
                    <span class="fw-bold fs-4"><?php echo e(strtoupper(substr($pelapor->nama ?? 'U', 0, 2))); ?></span>
                </div>
            </div>

            
            <div class="flex-grow-1">
                <h4 class="mb-1"><?php echo e($pelapor->nama); ?></h4>
                <p class="text-muted mb-1"><?php echo e($pelapor->email); ?></p>
                <span class="badge bg-secondary"><?php echo e(ucfirst($pelapor->jabatan ?? 'User')); ?></span>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted d-block">Jenis Kapal</small>
                    <span class="fw-bold"><?php echo e($pelapor->jenis_kapal ?? '-'); ?></span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Nomor Telepon</small>
                    <span class="fw-bold"><?php echo e($pelapor->phone_number ?? '-'); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Laporan</h5>
            <span class="badge bg-light text-dark border"><?php echo e($laporanKejadian->count()); ?> laporan</span>
        </div>
        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $laporanKejadian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                    <div>
                        <h6 class="mb-1"><?php echo e($laporan->nama_kapal); ?></h6>
                        <small class="text-muted">#<?php echo e(str_pad($laporan->id, 6, '0', STR_PAD_LEFT)); ?> •
                            <?php echo e($laporan->created_at->format('d M Y, H:i')); ?></small>
                    </div>
                    <div class="text-end">
                        <span
                            class="badge <?php echo e($laporan->status_laporan == 'dikirim' ? 'bg-primary' : ($laporan->status_laporan == 'diverifikasi' ? 'bg-success' : 'bg-secondary')); ?>">
                            <?php echo e(ucfirst($laporan->status_laporan)); ?>

                        </span>
                        <a href="<?php echo e(route('admin.laporan.show', $laporan)); ?>" class="btn btn-sm btn-outline-info ms-2">
                            Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-muted my-4">Pelapor ini belum membuat laporan.</p>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/admin/pelapor-detail.blade.php ENDPATH**/ ?>