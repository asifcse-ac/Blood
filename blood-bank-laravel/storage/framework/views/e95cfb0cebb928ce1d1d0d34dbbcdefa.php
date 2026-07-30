<?php $__env->startSection('title', 'My Blood Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> My Blood Requests</h5>
    </div>
    <div class="card-body">
        <?php if(count($requests) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Hospital</th>
                            <th>Urgency</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($req->request_id); ?></td>
                                <td><span class="badge bg-danger"><?php echo e($req->blood_group); ?></span></td>
                                <td><?php echo e($req->units_requested); ?></td>
                                <td><?php echo e($req->hospital_name); ?></td>
                                <td>
                                    <?php
                                        $urgencyClass = $req->urgency === 'Critical' ? 'danger' : ($req->urgency === 'Urgent' ? 'warning' : 'secondary');
                                    ?>
                                    <span class="badge bg-<?php echo e($urgencyClass); ?>"><?php echo e($req->urgency); ?></span>
                                </td>
                                <td>
                                    <?php
                                        $statusClass = $req->status === 'approved' ? 'success' : ($req->status === 'rejected' ? 'danger' : 'warning');
                                    ?>
                                    <span class="badge bg-<?php echo e($statusClass); ?>"><?php echo e(ucfirst($req->status)); ?></span>
                                </td>
                                <td><?php echo e($req->request_date->format('M d, Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($requests->links()); ?>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No blood requests yet</p>
                <a href="<?php echo e(route('user.request-blood')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Request Blood
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/user/my-requests.blade.php ENDPATH**/ ?>