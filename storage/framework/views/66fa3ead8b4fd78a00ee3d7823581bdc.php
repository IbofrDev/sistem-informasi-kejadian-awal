<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard')); ?>">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="KSOP Logo" height="40" class="d-inline-block me-2">
            <div>
                <span class="fw-bold">KSOP</span>
                <small class="d-block text-muted fw-normal" style="font-size: 0.7rem; line-height: 1;">Aplikasi Pelaporan</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <?php if(Auth::user()->role == 'admin'): ?>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">Semua Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('admin.users.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.index')); ?>">Kelola User</a>
                    </li>
                <?php else: ?>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Riwayat Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('laporan.create') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.create')); ?>">Buat Laporan Baru</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <?php echo e(Auth::user()->nama); ?>

                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                            Profile
                        </a>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/layouts/navigation-bootstrap.blade.php ENDPATH**/ ?>