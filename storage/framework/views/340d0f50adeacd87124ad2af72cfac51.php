<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - Register</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/scss/app.scss', 'resources/js/app.js']); ?>

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Figtree', sans-serif;
        }

        .register-page-wrapper {
            min-height: 100vh;
            background-image: url('/images/register.png');
            /* Pastikan gambar ini ada di public/images */
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
        }

        .register-card {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            /* Diperkecil dari 2rem */
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, .15);
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 450px;
            /* Lebar maksimal form diperkecil */
        }

        .register-card .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .register-card .form-control,
        .register-card .form-select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
        }

        .register-card .btn {
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            background-color: #211551;
            border-color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="register-page-wrapper">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="/">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="KSOP Logo" height="40"
                            class="d-inline-block me-2">
                        <div>
                            <span class="fw-bold">SIKAP</span>
                            <small class="d-block text-muted fw-normal"
                                style="font-size: 0.7rem; line-height: 1;">Sistem Informasi Kecelakaan Kapal</small>
                        </div>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">About Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main class="d-flex align-items-center justify-content-center flex-grow-1 py-3">
            <div class="register-card">
                <h2 class="text-center mb-4">Sign Up</h2>
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input id="nama" type="text" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            name="nama" value="<?php echo e(old('nama')); ?>" placeholder="Full Name..." required autofocus>
                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-2">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option selected disabled value="">Pilih Jabatan...</option>
                            <option value="Master/Nakhoda">Master/Nakhoda</option>
                            <option value="C/O (Chief Officer)">C/O (Chief Officer)</option>
                            <option value="2nd Officer">2nd Officer</option>
                            <option value="3rd Officer">3rd Officer</option>
                        </select>
                        <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-2">
                        <label for="jenis_kapal" class="form-label">Jenis Kapal</label>
                        <select id="jenis_kapal" name="jenis_kapal"
                            class="form-select <?php $__errorArgs = ['jenis_kapal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option selected disabled value="">Pilih Jenis Kapal...</option>
                            <option value="KM (Kapal Motor)">KM (Kapal Motor)</option>
                            <option value="MV (Motor Vessel)">MV (Motor Vessel)</option>
                            <option value="MT (Motor Tanker)">MT (Motor Tanker)</option>
                            <option value="SPOB (Self Propeller Oil Barge)">SPOB (Self Propeller Oil Barge)</option>
                            <option value="LCT (Landing Craft Tanker)">LCT (Landing Craft Tanker)</option>
                            <option value="TB (TUG Boat)">TB (TUG Boat)</option>
                            <option value="BG (Barge)">BG (Barge)</option>
                            <option value="FC (Floating Crane)">FC (Floating Crane)</option>
                            <option value="KLM (Kapal Layar Motor / Yacth)">KLM (Kapal Layar Motor / Yacth)</option>
                        </select>
                        <?php $__errorArgs = ['jenis_kapal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input id="phone_number" type="text"
                            class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone_number"
                            value="<?php echo e(old('phone_number')); ?>" placeholder="Nomor Telepon..." required>
                        <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            name="email" value="<?php echo e(old('email')); ?>" placeholder="Email..." required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                            placeholder="Password..." required>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control"
                            name="password_confirmation" placeholder="Password..." required>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
                <p class="text-center text-muted mt-3 mb-0">
                    Sudah punya akun? <a href="<?php echo e(url('/')); ?>">Login</a>
                </p>
            </div>
        </main>
    </div>
</body>

</html><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/auth/register.blade.php ENDPATH**/ ?>