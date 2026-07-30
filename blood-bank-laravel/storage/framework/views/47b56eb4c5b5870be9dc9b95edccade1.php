<?php $__env->startSection('title', 'Blood Stock Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Blood Stock Management</h2>
        <div class="page-header-sub">Manage blood inventory across all blood types</div>
    </div>
</div>

<!-- Stats Cards -->
<div class="stat-cards-row" style="grid-template-columns: repeat(2, 1fr);">
    <div class="sc sc-donors">
        <div class="sc-icon"><i class="fas fa-tint"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($totalUnits); ?></span>
            <span class="sc-label">Total Units Available</span>
        </div>
    </div>
    <div class="sc sc-pending">
        <div class="sc-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="sc-body">
            <span class="sc-num"><?php echo e($bloodStock->where('quantity', 0)->count()); ?></span>
            <span class="sc-label">Blood Types Empty</span>
        </div>
    </div>
</div>

<!-- Blood Stock Table -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fas fa-tint"></i> Blood Stock Overview
    </div>
    <div class="dash-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Blood Group</th>
                        <th>Current Units</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
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
                            <td>
                                <strong style="font-size: 18px; color: <?php echo e($qty > 5 ? '#16a34a' : ($qty > 0 ? '#d97706' : '#dc2626')); ?>"><?php echo e($qty); ?></strong> units
                            </td>
                            <td><span class="badge <?php echo e($badge[1]); ?>"><?php echo e($badge[0]); ?></span></td>
                            <td><?php echo e($stock->last_updated ? \Carbon\Carbon::parse($stock->last_updated)->diffForHumans() : 'N/A'); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addModal<?php echo e($stock->stock_id); ?>">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                                <?php if($qty > 0): ?>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#removeModal<?php echo e($stock->stock_id); ?>">
                                        <i class="fas fa-minus"></i> Remove
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Add Modal -->
                        <div class="modal fade" id="addModal<?php echo e($stock->stock_id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Units to <?php echo e($stock->blood_group); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?php echo e(route('admin.blood-stock.add', $stock)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Number of Units to Add</label>
                                                <input type="number" class="form-control" name="units" min="1" value="1" required>
                                            </div>
                                            <p class="text-muted">Current stock: <?php echo e($qty); ?> units</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Add Units</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Remove Modal -->
                        <div class="modal fade" id="removeModal<?php echo e($stock->stock_id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Remove Units from <?php echo e($stock->blood_group); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?php echo e(route('admin.blood-stock.remove', $stock)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Number of Units to Remove</label>
                                                <input type="number" class="form-control" name="units" min="1" max="<?php echo e($qty); ?>" value="1" required>
                                            </div>
                                            <p class="text-muted">Current stock: <?php echo e($qty); ?> units</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">Remove Units</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/blood-stock/index.blade.php ENDPATH**/ ?>