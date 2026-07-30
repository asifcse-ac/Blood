<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — BloodLink</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --crimson: #C41E3A;
            --crimson-light: #E63950;
            --cream: #FDF8F3;
            --ink: #18100E;
            --ink-soft: #5C4033;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .navbar-brand i { color: #ff6b6b; }
        
        .nav-link {
            font-weight: 500;
            transition: opacity .2s;
        }
        
        .nav-link:hover { opacity: .85; }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 15px;
        }
        
        .blood-card {
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform .2s;
        }
        
        .blood-card:hover { transform: translateY(-5px); }
        
        .blood-group-badge {
            font-size: 32px;
            font-weight: bold;
            color: #dc3545;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        }
        
        .btn-danger {
            background: var(--crimson);
            border-color: var(--crimson);
        }
        
        .btn-danger:hover {
            background: var(--crimson-light);
        }
        
        .badge-unread {
            background: #dc3545;
            font-size: 10px;
            padding: 3px 6px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,.15);
            border-radius: 12px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('user.dashboard')); ?>">
                <i class="fas fa-heartbeat"></i> Blood Bank System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('user.dashboard')); ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.request-blood') ? 'active' : ''); ?>" href="<?php echo e(route('user.request-blood')); ?>">
                            <i class="fas fa-hand-holding-heart"></i> Request Blood
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.requests.*') ? 'active' : ''); ?>" href="<?php echo e(route('user.requests.index')); ?>">
                            <i class="fas fa-list"></i> My Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.donors') ? 'active' : ''); ?>" href="<?php echo e(route('user.donors')); ?>">
                            <i class="fas fa-users"></i> Donors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.find-nearby') ? 'active' : ''); ?>" href="<?php echo e(route('user.find-nearby')); ?>">
                            <i class="fas fa-map-location-dot"></i> Find Nearby
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.track-stock') ? 'active' : ''); ?>" href="<?php echo e(route('user.track-stock')); ?>">
                            <i class="fas fa-chart-line"></i> Stock
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('user.ai-chat') ? 'active' : ''); ?>" href="<?php echo e(route('user.ai-chat')); ?>">
                            <i class="fas fa-robot"></i> AI Assistant
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?php $unreadCount = auth('user')->user()->unreadNotificationsCount(); ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="badge badge-unread position-absolute top-0 start-100 translate-middle"><?php echo e($unreadCount); ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:300px;">
                            <li class="dropdown-header"><strong>Notifications</strong></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="<?php echo e(route('user.notifications')); ?>">View All</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?php echo e(auth('user')->user()->full_name); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('user.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="<?php echo e(route('user.logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/layouts/user.blade.php ENDPATH**/ ?>