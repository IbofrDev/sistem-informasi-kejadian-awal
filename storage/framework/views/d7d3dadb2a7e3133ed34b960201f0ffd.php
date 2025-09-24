<section>
    <header>
        <h2 class="h5">
            <?php echo e(__('Delete Account')); ?>

        </h2>

        <p class="text-muted">
            <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')); ?>

        </p>
    </header>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <?php echo e(__('Delete Account')); ?>

    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?php echo e(route('profile.destroy')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('delete'); ?>

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel"><?php echo e(__('Are you sure you want to delete your account?')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')); ?></p>
                        
                        <div class="mb-3">
                            <label for="password-delete" class="form-label"><?php echo e(__('Password')); ?></label>
                            <input id="password-delete" name="password" type="password" class="form-control" placeholder="<?php echo e(__('Password')); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="btn btn-danger"><?php echo e(__('Delete Account')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>