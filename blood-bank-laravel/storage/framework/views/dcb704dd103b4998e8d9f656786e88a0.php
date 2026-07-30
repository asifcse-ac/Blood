<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> — BloodLink</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --crimson: #C41E3A;
            --crimson-light: #E63950;
            --cream: #FDF8F3;
            --ink: #18100E;
            --ink-soft: #5C4033;
            --warm-gray: #F0EBE5;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #FDF8F3 !important; font-family: 'DM Sans', sans-serif; }
        
        .sidebar {
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            background: linear-gradient(180deg, #18100E 0%, #2C0F18 100%);
            border-right: 1px solid rgba(196,30,58,.15);
            box-shadow: 4px 0 32px rgba(24,16,14,.14);
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 24px 20px 16px;
            border-bottom: 1px solid rgba(196,30,58,.15);
        }
        
        .sidebar-brand h4 {
            font-family: 'Instrument Serif', serif;
            color: #fff !important;
            font-size: 20px;
            margin: 0;
            letter-spacing: -.3px;
        }
        
        .sidebar-brand h4 i { color: #C41E3A; margin-right: 8px; }
        
        .sidebar-admin {
            padding: 12px 20px 16px;
            font-size: 12px;
            color: rgba(253,248,243,.45);
            font-weight: 500;
            letter-spacing: .5px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(196,30,58,.10);
        }
        
        .sidebar nav { padding: 10px 0; flex: 1; }
        
        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            color: rgba(253,248,243,.72) !important;
            text-decoration: none !important;
            padding: 11px 20px;
            font-size: 14px;
            font-weight: 500;
            transition: background .18s, color .18s;
            border-left: 3px solid transparent;
        }
        
        .sidebar nav a i { width: 18px; text-align: center; opacity: .75; font-size: 13px; }
        
        .sidebar nav a:hover {
            background: rgba(196,30,58,.12);
            color: #fff !important;
            border-left-color: rgba(196,30,58,.5);
        }
        
        .sidebar nav a.active {
            background: rgba(196,30,58,.18);
            color: #fff !important;
            border-left-color: #C41E3A;
        }
        
        .sidebar nav a.active i { opacity: 1; }
        
        .main-content {
            margin-left: 240px;
            padding: 32px 28px;
            min-height: 100vh;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        
        .page-header h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 32px;
            color: #18100E;
            letter-spacing: -.5px;
            margin: 0;
        }
        
        .page-header-sub {
            font-size: 13px;
            color: #5C4033;
            margin-top: 3px;
        }
        
        .stat-cards-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; margin-bottom: 28px; }
        
        .sc {
            border-radius: 20px;
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 36px rgba(24,16,14,.10);
        }
        
        .sc::after {
            content: '';
            position: absolute;
            right: -20px; top: -20px;
            width: 100px; height: 100px;
            background: rgba(255,255,255,.09);
            border-radius: 50%;
        }
        
        .sc-icon {
            width: 48px; height: 48px;
            background: rgba(255,255,255,.18);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
        }
        
        .sc-body { flex: 1; min-width: 0; }
        
        .sc-num {
            font-family: 'Instrument Serif', serif;
            font-size: 36px;
            line-height: 1;
            color: #fff !important;
            display: block;
        }
        
        .sc-label {
            font-size: 12px;
            color: rgba(255,255,255,.78) !important;
            font-weight: 500;
            margin-top: 4px;
            display: block;
        }
        
        .sc-donors { background: linear-gradient(135deg, #C41E3A, #8B0F24); }
        .sc-units { background: linear-gradient(135deg, #7c3aed, #5b21b6); }
        .sc-pending { background: linear-gradient(135deg, #d97706, #92400e); }
        .sc-users { background: linear-gradient(135deg, #0284c7, #075985); }
        
        .dash-card {
            background: rgba(255,252,249,.96);
            border: 1px solid rgba(196,30,58,.09);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(24,16,14,.07);
            margin-bottom: 24px;
        }
        
        .dash-card-header {
            padding: 16px 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Instrument Serif', serif;
            font-size: 17px;
            border-bottom: 1px solid rgba(196,30,58,.08);
        }
        
        .dash-card-header i { color: #C41E3A; }
        .dash-card-body { padding: 0; }
        
        .dash-card .table {
            margin: 0;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
        }
        
        .dash-card .table thead th {
            padding: 13px 18px !important;
            font-size: 10px !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
            font-weight: 700 !important;
            background: rgba(196,30,58,.05) !important;
            color: #C41E3A !important;
            border-bottom: 1px solid rgba(196,30,58,.08) !important;
            border-top: none !important;
        }
        
        .dash-card .table tbody td {
            padding: 13px 18px !important;
            border-bottom: 1px solid rgba(196,30,58,.05) !important;
            border-top: none !important;
            color: #18100E;
            font-size: 14px;
            vertical-align: middle !important;
        }
        
        .dash-card .table tbody tr:last-child td { border-bottom: none !important; }
        .dash-card .table tbody tr:hover td { background: rgba(196,30,58,.03); }
        
        .req-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(196,30,58,.06);
            gap: 12px;
        }
        
        .req-item:last-child { border-bottom: none; }
        .req-name { font-weight: 600; font-size: 14px; color: #18100E; }
        .req-meta { font-size: 12px; color: #5C4033; margin-top: 2px; }
        
        .badge {
            border-radius: 999px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 5px 12px !important;
        }
        
        .badge-good { background: rgba(22,163,74,.12); color: #15803d; }
        .badge-low { background: rgba(217,119,6,.12); color: #92400e; }
        .badge-empty { background: rgba(196,30,58,.12); color: #C41E3A; }
        .badge-approved { background: rgba(22,163,74,.12); color: #15803d; }
        .badge-pending { background: rgba(217,119,6,.12); color: #92400e; }
        .badge-rejected { background: rgba(196,30,58,.12); color: #C41E3A; }
        
        .blood-group-pill {
            display: inline-block;
            background: rgba(196,30,58,.10);
            color: #C41E3A;
            border-radius: 8px;
            padding: 3px 10px;
            font-family: 'Instrument Serif', serif;
            font-size: 15px;
            font-weight: 600;
        }
        
        .btn-crime {
            background: var(--crimson);
            color: white;
            border: none;
        }
        
        .btn-crime:hover {
            background: var(--crimson-light);
            color: white;
        }
        
        @media (max-width: 1100px) {
            .stat-cards-row { grid-template-columns: repeat(2,1fr); }
        }
        
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px 16px; }
            .stat-cards-row { grid-template-columns: 1fr 1fr; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        <h4><i class="fas fa-heartbeat"></i> BBMS Admin</h4>
    </div>
    <div class="sidebar-admin"><?php echo e(auth('admin')->user()->full_name ?? 'Admin'); ?></div>
    <nav>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="<?php echo e(route('admin.donors.index')); ?>" class="<?php echo e(request()->routeIs('admin.donors.*') ? 'active' : ''); ?>">
            <i class="fas fa-users"></i> Manage Donors
        </a>
        <a href="<?php echo e(route('admin.donors.locations')); ?>" class="<?php echo e(request()->routeIs('admin.donors.locations') ? 'active' : ''); ?>">
            <i class="fas fa-map-location-dot"></i> Donor Locations
        </a>
        <a href="<?php echo e(route('admin.blood-stock.index')); ?>" class="<?php echo e(request()->routeIs('admin.blood-stock.*') ? 'active' : ''); ?>">
            <i class="fas fa-tint"></i> Blood Stock
        </a>
        <a href="<?php echo e(route('admin.requests.index')); ?>" class="<?php echo e(request()->routeIs('admin.requests.*') ? 'active' : ''); ?>">
            <i class="fas fa-clipboard-list"></i> Blood Requests
        </a>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-friends"></i> All Users
        </a>
        <a href="<?php echo e(route('admin.reports.index')); ?>" class="<?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
        <a href="<?php echo e(route('admin.ai-chat')); ?>" class="<?php echo e(request()->routeIs('admin.ai-chat') ? 'active' : ''); ?>">
            <i class="fas fa-robot"></i> AI Assistant
        </a>
        <a href="<?php echo e(route('admin.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>

        <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
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
<?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/layouts/admin.blade.php ENDPATH**/ ?>