<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-bell"></i> My Notifications</h5>
        <form action="<?php echo e(route('user.notifications.read-all')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-light">Mark All as Read</button>
        </form>
    </div>
    <div class="card-body">
        <?php if(count($notifications) > 0): ?>
            <div class="list-group">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item <?php echo e($notif->is_read ? '' : 'list-group-item-info'); ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><?php echo e($notif->message); ?></p>
                                <small class="text-muted"><?php echo e($notif->created_at->format('M d, Y h:i A')); ?></small>
                            </div>
                            <?php if(!$notif->is_read): ?>
                                <form action="<?php echo e(route('user.notifications.read', $notif)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark Read</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php echo e($notifications->links()); ?>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No notifications</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/user/notifications.blade.php ENDPATH**/ ?>