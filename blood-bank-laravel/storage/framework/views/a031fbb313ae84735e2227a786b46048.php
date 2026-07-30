<?php $__env->startSection('title', 'Edit Donor'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Edit Donor</h2>
        <div class="page-header-sub">Update donor information</div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-body p-4">
        <form action="<?php echo e(route('admin.donors.update', $donor)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" value="<?php echo e($donor->full_name); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Blood Group *</label>
                    <select class="form-select" name="blood_group" required>
                        <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($group); ?>" <?php echo e($donor->blood_group === $group ? 'selected' : ''); ?>><?php echo e($group); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Age *</label>
                    <input type="number" class="form-control" name="age" min="18" max="65" value="<?php echo e($donor->age); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gender *</label>
                    <select class="form-select" name="gender" required>
                        <?php $__currentLoopData = ['Male', 'Female', 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gender); ?>" <?php echo e($donor->gender === $gender ? 'selected' : ''); ?>><?php echo e($gender); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone *</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo e($donor->phone); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo e($donor->email); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Donation Date</label>
                    <input type="date" class="form-control" name="last_donation_date" value="<?php echo e($donor->last_donation_date?->format('Y-m-d')); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2"><?php echo e($donor->address); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" class="form-control" name="latitude" value="<?php echo e($donor->latitude); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" class="form-control" name="longitude" value="<?php echo e($donor->longitude); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status *</label>
                    <select class="form-select" name="status" required>
                        <option value="active" <?php echo e($donor->status === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e($donor->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-crime">
                    <i class="fas fa-save"></i> Update Donor
                </button>
                <a href="<?php echo e(route('admin.donors.index')); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/donors/edit.blade.php ENDPATH**/ ?>