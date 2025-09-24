<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sistem Kejadian Awal'); ?></title>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .nav-pills .nav-link.active {
            background-color: #E9C217 !important;
            color: #1f1f1f !important;
        }
    </style>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/scss/app.scss', 'resources/js/app.js']); ?>
</head>

<body class="bg-light">

    <div class="d-flex">
        <nav class="sidebar vh-100 text-white p-3 shadow-sm" style="width: 280px; position: fixed; background: linear-gradient(to bottom, #28166F, #120A30);">
            <a href="/" class="d-flex align-items-center mb-4 text-white text-decoration-none">
                <img src="<?php echo e(asset('images/logo_ksop.png')); ?>" alt="Logo KSOP" style="width: 40px; height: auto;"
                    class="me-3">
                <div>
                    <span class="fs-5 fw-bold d-block" style="line-height: 1;">SIKAP</span>
                    <small class="text-white-50" style="font-size: 0.8rem; line-height: 1;">
                        Sistem Informasi Kecelakaan Kapal
                    </small>
                </div>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">

                <?php if(Auth::user()->role == 'admin'): ?>
                    <li class="nav-item mb-1">
                        <a href="<?php echo e(route('admin.dashboard')); ?>"
                            class="nav-link text-white <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <i class="bi bi-speedometer2 me-2"></i> <strong>Dashboard</strong>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        
                        <a href="<?php echo e(route('admin.users.index')); ?>"
                            class="nav-link text-white <?php echo e(request()->routeIs('admin.users.index') ? 'active' : ''); ?>">
                            <i class="bi bi-people-fill me-2"></i> <strong>Kelola User</strong>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item mb-1">
                        <a href="<?php echo e(route('dashboard')); ?>"
                            class="nav-link text-white <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                            <i class="bi bi-file-earmark-text-fill me-2"></i> Laporan Saya
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?php echo e(route('laporan.create')); ?>"
                            class="nav-link text-white <?php echo e(request()->routeIs('laporan.create') ? 'active' : ''); ?>">
                            <i class="bi bi-plus-circle-fill me-2"></i> Buat Laporan Baru
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>

        <main class="main-content" style="margin-left: 280px; width: calc(100% - 280px);">
            <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                <div class="container-fluid">
                    <h4 class="mb-0 fw-bold"><?php echo $__env->yieldContent('page-title', 'Halaman Utama'); ?></h4>

                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <span class="d-none d-sm-inline"><?php echo e(Auth::user()->nama ?? 'Pengguna'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                            <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="container-fluid p-4">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/layouts/app.blade.php ENDPATH**/ ?>