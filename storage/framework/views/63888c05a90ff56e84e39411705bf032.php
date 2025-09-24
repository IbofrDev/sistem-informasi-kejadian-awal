<section>
    <header>
        <h2 class="h5">
            <?php echo e(__('Profile Information')); ?>

        </h2>

        <p class="text-muted">
            <?php echo e(__("Update your account's profile information and email address.")); ?>

        </p>
    </header>

    <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
    </form>

    <form method="post" action="<?php echo e(route('profile.update')); ?>" class="mt-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>

        <div class="mb-3">
            <label for="nama" class="form-label"><?php echo e(__('Nama')); ?></label>
            <input id="nama" name="nama" type="text" class="form-control" value="<?php echo e(old('nama', $user->nama)); ?>" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
            <input id="email" name="email" type="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>

            <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                <div>
                    <p class="text-sm mt-2 text-muted">
                        <?php echo e(__('Your email address is unverified.')); ?>

                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline"><?php echo e(__('Click here to re-send the verification email.')); ?></button>
                    </p>
                    <?php if(session('status') === 'verification-link-sent'): ?>
                        <p class="mt-2 fw-medium text-sm text-success">
                            <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>

            <?php if(session('status') === 'profile-updated'): ?>
                <p class="text-success mb-0"><?php echo e(__('Saved.')); ?></p>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>