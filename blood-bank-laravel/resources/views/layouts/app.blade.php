<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blood Bank Management System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --crimson: #C41E3A;
            --crimson-light: #E63950;
            --cream: #FDF8F3;
            --ink: #18100E;
            --ink-soft: #5C4033;
            --warm-gray: #F0EBE5;
            --card-bg: rgba(255,252,249,.92);
            --shadow-warm: 0 32px 80px rgba(196,30,58,.10);
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        
        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: .028;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
        }
        
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
            animation: drift 18s ease-in-out infinite alternate;
        }
        
        .blob-1 {
            width: 520px;
            height: 520px;
            background: rgba(196,30,58,.12);
            top: -120px;
            right: -100px;
        }
        
        .blob-2 {
            width: 380px;
            height: 380px;
            background: rgba(230,100,60,.08);
            bottom: 10%;
            left: -80px;
            animation-delay: -6s;
        }
        
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to { transform: translate(30px,20px) scale(1.06); }
        }
        
        .nav-wrap {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 18px 0;
            transition: background .3s, padding .3s;
        }
        
        .nav-wrap.scrolled {
            background: rgba(253,248,243,.88);
            backdrop-filter: blur(20px);
            padding: 10px 0;
            box-shadow: 0 1px 0 rgba(196,30,58,.08), 0 8px 32px rgba(24,16,14,.06);
        }
        
        .nav-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--crimson);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .brand-name {
            font-family: 'Instrument Serif', serif;
            font-size: 22px;
            color: var(--ink);
            letter-spacing: -.3px;
        }
        
        .brand-name span { color: var(--crimson); }
        
        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .nav-btn {
            padding: 9px 22px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            transition: all .22s;
            cursor: pointer;
            border: none;
        }
        
        .nav-btn-ghost {
            background: transparent;
            color: var(--ink-soft);
            border: 1.5px solid rgba(196,30,58,.25);
        }
        
        .nav-btn-ghost:hover {
            background: rgba(196,30,58,.06);
            border-color: var(--crimson);
            color: var(--crimson);
        }
        
        .nav-btn-solid {
            background: var(--crimson);
            color: white;
            box-shadow: 0 8px 24px rgba(196,30,58,.28);
        }
        
        .nav-btn-solid:hover {
            background: var(--crimson-light);
            transform: translateY(-1px);
            color: white;
        }
        
        .page-footer {
            background: var(--ink);
            padding: 48px 28px;
            text-align: center;
        }
        
        .footer-brand {
            font-family: 'Instrument Serif', serif;
            font-size: 26px;
            color: var(--cream);
        }
        
        .footer-brand span { color: var(--crimson); }
        
        .footer-sub {
            font-size: 14px;
            color: rgba(253,248,243,.4);
            margin-top: 10px;
        }
        
        .fade-up {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .7s ease, transform .7s ease;
        }
        
        .fade-up.in-view {
            opacity: 1;
            transform: none;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .badge {
            border-radius: 999px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 5px 12px !important;
        }
        
        @media (max-width: 768px) {
            .nav-links { flex-wrap: wrap; gap: 8px; }
            .nav-btn { padding: 8px 16px; font-size: 13px; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    @if(!request()->routeIs('admin.*'))
    <!-- NAVBAR -->
    <nav class="nav-wrap" id="navbar">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="brand">
                <div class="brand-icon"><i class="fas fa-heartbeat"></i></div>
                <span class="brand-name">Blood<span>Link</span></span>
            </a>
            <div class="nav-links">
                @auth('admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-solid">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.logout') }}" class="nav-btn nav-btn-ghost" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @elseifauth('user')
                    <a href="{{ route('user.dashboard') }}" class="nav-btn nav-btn-solid">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('user.logout') }}" class="nav-btn nav-btn-ghost" onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="nav-btn nav-btn-ghost">Admin</a>
                    <a href="{{ route('user.login') }}" class="nav-btn nav-btn-solid">Sign In</a>
                @endauth
            </div>
        </div>
    </nav>
    @endif
    
    @yield('content')
    
    @if(!request()->routeIs('admin.*'))
    <!-- FOOTER -->
    <footer class="page-footer">
        <div class="footer-brand">Blood<span>Link</span></div>
        <div class="footer-sub">© {{ date('Y') }} Blood Bank Management System. Built to save lives.</div>
    </footer>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        if (navbar) {
            window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 40));
        }
        
        // Fade up animation
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in-view'); });
        }, { threshold: 0.12 });
        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    </script>
    @stack('scripts')
</body>
</html>
