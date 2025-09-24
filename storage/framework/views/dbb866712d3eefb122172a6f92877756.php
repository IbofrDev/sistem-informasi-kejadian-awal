

<?php $__env->startSection('title', 'Kelola User'); ?>
<?php $__env->startSection('page-title', 'Daftar User'); ?>

<?php $__env->startSection('content'); ?>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama atau email..."
                    value="<?php echo e(request('search')); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> <span class="d-none d-sm-inline">Cari</span>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <span class="fw-bold fs-5"><?php echo e(strtoupper(substr($user->nama, 0, 2))); ?></span>
                                </div>
                            </div>

                            
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0"><?php echo e($user->nama); ?></h5>
                                <p class="card-text text-muted small mb-0"><?php echo e($user->email); ?></p>
                            </div>
                        </div>

                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-file-earmark-check me-1"></i>
                                    <?php echo e($user->laporan_kejadian_count); ?> Laporan
                                </span>
                            </div>
                            <div>
                                <span class="badge <?php echo e($user->role == 'admin' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        
                        <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST"
                            onsubmit="return confirm('Anda yakin ingin menghapus user ini? Semua laporan yang terkait akan ikut terhapus.');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger" <?php echo e(auth()->id() == $user->id ? 'disabled' : ''); ?>>
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h5 class="alert-heading">Tidak Ditemukan</h5>
                    <p class="mb-0">Tidak ada user yang cocok dengan pencarian Anda.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    
    <?php if($users->hasPages()): ?>
        <div class="mt-4 d-flex justify-content-center">
            <nav>
                <ul class="pagination mb-0">
                    
                    <?php if($users->onFirstPage()): ?>
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link"><i class="bi bi-arrow-left"></i></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($users->previousPageUrl()); ?>" rel="prev">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = $users->getUrlRange(1, $users->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $users->currentPage()): ?>
                            <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($users->hasMorePages()): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($users->nextPageUrl()); ?>" rel="next">
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
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/admin/users/index.blade.php ENDPATH**/ ?>