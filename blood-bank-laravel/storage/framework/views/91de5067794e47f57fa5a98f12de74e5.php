<?php $__env->startSection('title', 'Donors Directory'); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-users"></i> Active Donors</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select class="form-select" name="blood_group">
                    <option value="">All Blood Groups</option>
                    <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group); ?>" <?php echo e(request('blood_group') === $group ? 'selected' : ''); ?>><?php echo e($group); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search by name or location" value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="<?php echo e(route('user.donors')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <?php if(count($donors) > 0): ?>
            <div class="row">
                <?php $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?php echo e($donor->full_name); ?></h5>
                                    <span class="badge bg-danger"><?php echo e($donor->blood_group); ?></span>
                                </div>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-phone"></i> <?php echo e($donor->phone); ?>

                                </p>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo e($donor->address ?? 'Not specified'); ?>

                                </p>
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-calendar"></i> Age: <?php echo e($donor->age); ?> | <?php echo e($donor->gender); ?>

                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="tel:<?php echo e($donor->phone); ?>" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-phone"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php echo e($donors->links()); ?>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No donors found</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/user/donors.blade.php ENDPATH**/ ?>