<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <div class="page-header-sub">Welcome back, <?php echo e(auth('admin')->user()->full_name); ?> · <?php echo e(date('l, F j, Y')); ?></div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-cards-row">
    <div class="sc sc-donors">
        <div class="sc-icon"><i class="fas fa-users"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($stats['donors']); ?></span>
            <span class="sc-label">Active Donors</span>
        </div>
    </div>
    <div class="sc sc-units">
        <div class="sc-icon"><i class="fas fa-tint"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($stats['units']); ?></span>
            <span class="sc-label">Total Units</span>
        </div>
    </div>
    <div class="sc sc-pending">
        <div class="sc-icon"><i class="fas fa-clock"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($stats['pending']); ?></span>
            <span class="sc-label">Pending Requests</span>
        </div>
    </div>
    <div class="sc sc-users">
        <div class="sc-icon"><i class="fas fa-user-friends"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($stats['users']); ?></span>
            <span class="sc-label">Registered Users</span>
        </div>
    </div>
</div>

<!-- BOTTOM ROW -->
<div class="row g-4">
    <!-- Blood Stock -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-tint"></i> Blood Stock Overview
            </div>
            <div class="dash-card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $bloodStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $qty = (int) $stock->quantity;
                                $badge = $qty > 5 ? ['Good','badge-good'] : ($qty > 0 ? ['Low','badge-low'] : ['Empty','badge-empty']);
                            ?>
                            <tr>
                                <td><span class="blood-group-pill"><?php echo e($stock->blood_group); ?></span></td>
                                <td><strong><?php echo e($qty); ?></strong></td>
                                <td><span class="badge <?php echo e($badge[1]); ?>"><?php echo e($badge[0]); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-clipboard-list"></i> Recent Requests
            </div>
            <div class="dash-card-body">
                <?php if(count($recentRequests) > 0): ?>
                    <?php $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $st = $req->status;
                            $badge_cls = $st === 'approved' ? 'badge-approved' : ($st === 'rejected' ? 'badge-rejected' : 'badge-pending');
                        ?>
                        <div class="req-item">
                            <div>
                                <div class="req-name"><?php echo e($req->user->full_name); ?></div>
                                <div class="req-meta">
                                    <span class="blood-group-pill" style="font-size:12px;padding:2px 8px"><?php echo e($req->blood_group); ?></span>
                                    &nbsp;<?php echo e($req->units_requested); ?> unit<?php echo e($req->units_requested != 1 ? 's' : ''); ?>

                                    &nbsp;·&nbsp;<?php echo e($req->request_date->format('M d, Y')); ?>

                                </div>
                            </div>
                            <span class="badge <?php echo e($badge_cls); ?>"><?php echo e(ucfirst($st)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:14px 20px; border-top:1px solid rgba(196,30,58,.06);">
                        <a href="<?php echo e(route('admin.requests.index')); ?>" class="btn btn-sm btn-outline-danger">View All Requests</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.3"></i>
                        No recent requests
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>