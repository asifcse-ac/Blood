<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="hero-section text-center">
    <h1><i class="fas fa-tint"></i> Welcome, <?php echo e(auth('user')->user()->full_name); ?>!</h1>
    <p class="lead">Manage blood requests and view availability</p>
    <?php if(auth('user')->user()->blood_group): ?>
        <h3>Your Blood Group: <span class="badge bg-danger"><?php echo e(auth('user')->user()->blood_group); ?></span></h3>
    <?php endif; ?>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3 class="mb-3"><i class="fas fa-tint"></i> Blood Availability</h3>
    </div>
    <?php $__currentLoopData = $bloodStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $statusClass = $stock->quantity > 5 ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger');
            $statusText = $stock->quantity > 5 ? 'Available' : ($stock->quantity > 0 ? 'Limited' : 'Unavailable');
        ?>
        <div class="col-md-3">
            <div class="blood-card">
                <div class="blood-group-badge"><?php echo e($stock->blood_group); ?></div>
                <hr>
                <h4 class="text-<?php echo e($statusClass); ?>"><?php echo e($stock->quantity); ?> Units</h4>
                <span class="badge bg-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> My Recent Requests</h5>
            </div>
            <div class="card-body">
                <?php if(count($myRequests) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $myRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($req->request_date->format('M d, Y')); ?></td>
                                        <td><span class="badge bg-danger"><?php echo e($req->blood_group); ?></span></td>
                                        <td><?php echo e($req->units_requested); ?></td>
                                        <td><?php echo e($req->hospital_name); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($req->getStatusBadgeClass()); ?>">
                                                <?php echo e(ucfirst($req->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo e(route('user.requests.index')); ?>" class="btn btn-sm btn-primary">View All Requests</a>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No requests yet</p>
                        <a href="<?php echo e(route('user.request-blood')); ?>" class="btn btn-primary">Request Blood Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Active Donors</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo e($donor->full_name); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-tint text-danger"></i> <?php echo e($donor->blood_group); ?>

                                    </small>
                                </div>
                                <span class="badge bg-danger"><?php echo e($donor->blood_group); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <a href="<?php echo e(route('user.donors')); ?>" class="btn btn-sm btn-success mt-3 w-100">View All Donors</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/user/dashboard.blade.php ENDPATH**/ ?>