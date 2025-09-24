<?php $__env->startSection('title', 'Dashboard Pelapor'); ?>
<?php $__env->startSection('page-title', 'Dashboard Anda'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title">Selamat Datang, <?php echo e(Auth::user()->name); ?>!</h4>
            <p class="card-text text-muted">Di sini Anda dapat melihat riwayat laporan yang telah Anda buat dan mengelola laporan Anda.</p>
            <a href="<?php echo e(route('laporan.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-2"></i>Buat Laporan Baru
            </a>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header">
            <h6 class="m-0 fw-bold text-primary">Riwayat Laporan Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $laporanKejadian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>#<?php echo e($laporan->id); ?></td>
                                <td><?php echo e($laporan->created_at->format('d M Y, H:i')); ?></td>
                                <td>
                                    
                                    <?php if($laporan->status_laporan == 'diverifikasi'): ?>
                                        <span class="badge bg-warning text-dark"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php elseif($laporan->status_laporan == 'selesai'): ?>
                                        <span class="badge bg-success"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('laporan.show', $laporan->id)); ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    <?php if($laporan->status_laporan != 'selesai'): ?>
                                    <a href="<?php echo e(route('laporan.edit', $laporan->id)); ?>" class="btn btn-sm btn-warning" title="Edit Laporan">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="mb-2">Anda belum membuat laporan apapun.</p>
                                    <a href="<?php echo e(route('laporan.create')); ?>" class="btn btn-sm btn-primary">Buat Laporan Pertama Anda</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/dashboard.blade.php ENDPATH**/ ?>