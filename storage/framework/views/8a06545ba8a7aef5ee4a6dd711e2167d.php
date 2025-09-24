

<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('page-title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit User</h2>
            <small class="text-muted">Mengubah data untuk <?php echo e($user->nama); ?></small>
        </div>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Batal & Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo e(old('nama', $user->nama)); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select" required>
                             <option disabled value="">Pilih Jabatan...</option>
                             <option value="Master/Nakhoda" <?php echo e(old('jabatan', $user->jabatan) == 'Master/Nakhoda' ? 'selected' : ''); ?>>Master/Nakhoda</option>
                             <option value="C/O (Chief Officer)" <?php echo e(old('jabatan', $user->jabatan) == 'C/O (Chief Officer)' ? 'selected' : ''); ?>>C/O (Chief Officer)</option>
                             <option value="2nd Officer" <?php echo e(old('jabatan', $user->jabatan) == '2nd Officer' ? 'selected' : ''); ?>>2nd Officer</option>
                             <option value="3rd Officer" <?php echo e(old('jabatan', $user->jabatan) == '3rd Officer' ? 'selected' : ''); ?>>3rd Officer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kapal" class="form-label">Jenis Kapal</label>
                        <select id="jenis_kapal" name="jenis_kapal" class="form-select" required>
                            <option disabled value="">Pilih Jenis Kapal...</option>
                            <option value="KM (Kapal Motor)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'KM (Kapal Motor)' ? 'selected' : ''); ?>>KM (Kapal Motor)</option>
                            <option value="MV (Motor Vessel)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'MV (Motor Vessel)' ? 'selected' : ''); ?>>MV (Motor Vessel)</option>
                            <option value="MT (Motor Tanker)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'MT (Motor Tanker)' ? 'selected' : ''); ?>>MT (Motor Tanker)</option>
                            <option value="SPOB (Self Propeller Oil Barge)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'SPOB (Self Propeller Oil Barge)' ? 'selected' : ''); ?>>SPOB (Self Propeller Oil Barge)</option>
                            <option value="LCT (Landing Craft Tanker)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'LCT (Landing Craft Tanker)' ? 'selected' : ''); ?>>LCT (Landing Craft Tanker)</option>
                            <option value="TB (TUG Boat)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'TB (TUG Boat)' ? 'selected' : ''); ?>>TB (TUG Boat)</option>
                            <option value="BG (Barge)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'BG (Barge)' ? 'selected' : ''); ?>>BG (Barge)</option>
                            <option value="FC (Floating Crane)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'FC (Floating Crane)' ? 'selected' : ''); ?>>FC (Floating Crane)</option>
                            <option value="KLM (Kapal Layar Motor / Yacth)" <?php echo e(old('jenis_kapal', $user->jenis_kapal) == 'KLM (Kapal Layar Motor / Yacth)' ? 'selected' : ''); ?>>KLM (Kapal Layar Motor / Yacth)</option>
                        </select>
                    </div>

                    
                    <div class="col-md-12 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select" required <?php echo e(auth()->id() == $user->id ? 'disabled' : ''); ?>>
                            <option value="pelapor" <?php echo e(old('role', $user->role) == 'pelapor' ? 'selected' : ''); ?>>Pelapor</option>
                            <option value="admin" <?php echo e(old('role', $user->role) == 'admin' ? 'selected' : ''); ?>>Admin</option>
                        </select>
                        <?php if(auth()->id() == $user->id): ?>
                            <small class="text-muted">Anda tidak dapat mengubah role akun Anda sendiri.</small>
                            <input type="hidden" name="role" value="<?php echo e($user->role); ?>">
                        <?php endif; ?>
                    </div>

                    <hr class="my-3">
                    <h5 class="mb-3">Ubah Password (Opsional)</h5>

                    
                     <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>