<?php $__env->startSection('title', 'Request Blood'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="fas fa-hand-holding-heart"></i> Request Blood</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('user.request-blood')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Group Required *</label>
                            <select class="form-select" name="blood_group" id="blood_group" required onchange="checkAvailability()">
                                <option value="">Select Blood Group</option>
                                <?php $__currentLoopData = $bloodStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($stock->blood_group); ?>" data-quantity="<?php echo e($stock->quantity); ?>">
                                        <?php echo e($stock->blood_group); ?> (<?php echo e($stock->quantity); ?> units available)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div id="availability-info" class="mt-2"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Units Required *</label>
                            <input type="number" class="form-control" name="units_requested" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hospital Name *</label>
                        <input type="text" class="form-control" name="hospital_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urgency Level *</label>
                        <select class="form-select" name="urgency" required>
                            <option value="Normal">Normal</option>
                            <option value="Urgent">Urgent</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for Request</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Please provide details about why you need blood"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Your request will be reviewed by admin. You will be notified once processed.
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function checkAvailability() {
        const select = document.getElementById('blood_group');
        const selectedOption = select.options[select.selectedIndex];
        const quantity = selectedOption.getAttribute('data-quantity');
        const infoDiv = document.getElementById('availability-info');
        
        if (quantity && quantity > 0) {
            infoDiv.innerHTML = `<span class="badge bg-success"><i class="fas fa-check"></i> ${quantity} units available</span>`;
        } else if (quantity !== null) {
            infoDiv.innerHTML = `<span class="badge bg-danger"><i class="fas fa-times"></i> Not available</span>`;
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/user/request-blood.blade.php ENDPATH**/ ?>