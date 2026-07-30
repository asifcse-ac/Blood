<?php $__env->startSection('title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Manage Users</h2>
        <div class="page-header-sub">View and manage all registered users</div>
    </div>
</div>

<!-- Filters -->
<div class="dash-card mb-4">
    <div class="dash-card-body p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Blood Group</label>
                <select class="form-select" name="blood_group">
                    <option value="">All Blood Groups</option>
                    <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group); ?>" <?php echo e(request('blood_group') === $group ? 'selected' : ''); ?>><?php echo e($group); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, email, username">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-crime d-block w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fas fa-users"></i> Registered Users
    </div>
    <div class="dash-card-body">
        <?php if(count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($user->user_id); ?></td>
                                <td><strong><?php echo e($user->full_name); ?></strong></td>
                                <td><?php echo e($user->username); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($user->blood_group): ?>
                                        <span class="blood-group-pill"><?php echo e($user->blood_group); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->status === 'active'): ?>
                                        <span class="badge badge-approved">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-rejected">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($user->created_at->format('M d, Y')); ?></td>
                                <td>
                                    <form action="<?php echo e(route('admin.users.update-status', $user)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="<?php echo e($user->status === 'active' ? 'inactive' : 'active'); ?>">
                                        <button type="submit" class="btn btn-sm <?php echo e($user->status === 'active' ? 'btn-warning' : 'btn-success'); ?>">
                                            <i class="fas fa-<?php echo e($user->status === 'active' ? 'ban' : 'check'); ?>"></i>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?')">
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
                <?php echo e($users->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-2x mb-3 d-block" style="opacity:.3"></i>
                No users found
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/users/index.blade.php ENDPATH**/ ?>