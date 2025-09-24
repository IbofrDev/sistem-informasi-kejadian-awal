<section>
    <header>
        <h2 class="h5">
            <?php echo e(__('Update Password')); ?>

        </h2>

        <p class="text-muted">
            <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

        </p>
    </header>

    <form method="post" action="<?php echo e(route('password.update')); ?>" class="mt-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        <div class="mb-3">
            <label for="current_password" class="form-label"><?php echo e(__('Current Password')); ?></label>
            <input id="current_password" name="current_password" type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label"><?php echo e(__('New Password')); ?></label>
            <input id="password" name="password" type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>

            <?php if(session('status') === 'password-updated'): ?>
                <p class="text-success mb-0"><?php echo e(__('Saved.')); ?></p>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>