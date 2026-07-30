<?php $__env->startSection('title', 'Manage Donors'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Manage Donors</h2>
        <div class="page-header-sub">View and manage all registered blood donors</div>
    </div>
    <a href="<?php echo e(route('admin.donors.create')); ?>" class="btn btn-crime">
        <i class="fas fa-plus"></i> Add New Donor
    </a>
</div>

<div class="dash-card">
    <div class="dash-card-body">
        <?php if(count($donors) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Blood Group</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($donor->donor_id); ?></td>
                                <td><strong><?php echo e($donor->full_name); ?></strong></td>
                                <td><span class="blood-group-pill"><?php echo e($donor->blood_group); ?></span></td>
                                <td><?php echo e($donor->age); ?></td>
                                <td><?php echo e($donor->gender); ?></td>
                                <td><?php echo e($donor->phone); ?></td>
                                <td><?php echo e($donor->address ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($donor->status === 'active'): ?>
                                        <span class="badge badge-approved">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-rejected">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.donors.edit', $donor)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.donors.destroy', $donor)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this donor?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                <?php echo e($donors->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-2x mb-3 d-block" style="opacity:.3"></i>
                No donors found
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/donors/index.blade.php ENDPATH**/ ?>