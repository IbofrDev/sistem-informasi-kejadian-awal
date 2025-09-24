

<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Laporan</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($totalLaporan); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-file-earmark-text-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Laporan Baru</div>
                            
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($laporanBaru); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-inbox-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Perlu Diverifikasi</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($perluVerifikasi); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-patch-question-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Laporan Selesai</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo e($laporanSelesai); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-check-circle-fill fs-2 text-secondary"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Daftar Laporan Kejadian</h6>

            
            <div class="btn-group" role="group" aria-label="Filter Status Laporan">
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="btn btn-sm <?php echo e(!$selectedStatus ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    Semua
                </a>
                <a href="<?php echo e(route('admin.dashboard', ['status' => 'dikirim'])); ?>"
                    class="btn btn-sm <?php echo e($selectedStatus == 'dikirim' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    Dikirim
                </a>
                <a href="<?php echo e(route('admin.dashboard', ['status' => 'diverifikasi'])); ?>"
                    class="btn btn-sm <?php echo e($selectedStatus == 'diverifikasi' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    Diverifikasi
                </a>
                <a href="<?php echo e(route('admin.dashboard', ['status' => 'selesai'])); ?>"
                    class="btn btn-sm <?php echo e($selectedStatus == 'selesai' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    Selesai
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelapor</th>
                            <th>Tanggal Kejadian</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $semuaLaporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>#<?php echo e($laporan->id); ?></td>
                                <td><?php echo e($laporan->user->nama ?? $laporan->nama_pelapor ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y, H:i')); ?></td>
                                <td>
                                    <?php if($laporan->status_laporan == 'diverifikasi'): ?>
                                        <span class="badge bg-warning text-dark"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php elseif($laporan->status_laporan == 'selesai'): ?>
                                        <span class="badge bg-success"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($laporan->status_laporan)); ?></span>
                                    <?php endif; ?>
                                </td>
<td class="text-center d-flex justify-content-center gap-1">
    
    <form action="<?php echo e(route('admin.laporan.updateStatus', $laporan->id)); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>
        <select name="status" class="form-select form-select-sm d-inline-block w-auto"
                onchange="this.form.submit()">
            <option value="dikirim" <?php echo e($laporan->status_laporan == 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
            <option value="diverifikasi" <?php echo e($laporan->status_laporan == 'diverifikasi' ? 'selected' : ''); ?>>Diverifikasi</option>
            <option value="selesai" <?php echo e($laporan->status_laporan == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
        </select>
    </form>

    
    <a href="<?php echo e(route('admin.laporan.show', $laporan->id)); ?>" class="btn btn-sm btn-info"
       title="Detail"><i class="bi bi-eye-fill"></i></a>

    
    <a href="<?php echo e(route('admin.laporan.print', $laporan->id)); ?>" class="btn btn-sm btn-secondary"
       title="Cetak PDF"><i class="bi bi-printer-fill"></i></a>
</td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data laporan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-3 d-flex justify-content-center">
                <?php if($semuaLaporan->hasPages()): ?>
                    <nav>
                        <ul class="pagination mb-0">
                            
                            <?php if($semuaLaporan->onFirstPage()): ?>
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link"><i class="bi bi-arrow-left"></i></span>
                                </li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($semuaLaporan->previousPageUrl()); ?>" rel="prev">
                                        <i class="bi bi-arrow-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            
                            <?php $__currentLoopData = $semuaLaporan->getUrlRange(1, $semuaLaporan->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $semuaLaporan->currentPage()): ?>
                                    <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php if($semuaLaporan->hasMorePages()): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($semuaLaporan->nextPageUrl()); ?>" rel="next">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link"><i class="bi bi-arrow-right"></i></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>