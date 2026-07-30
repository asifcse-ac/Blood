@extends('layouts.app')

@section('title', 'BloodLink — Save Lives, Donate Blood')

@section('content')
<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-left fade-up">
            <div class="hero-eyebrow">
                <div class="hero-eyebrow-dot"></div>
                Blood Bank Management System
            </div>
            <h1 class="hero-title">Save <em>Lives,</em><br>Donate Blood</h1>
            <p class="hero-sub">Every two seconds, someone needs blood. Your donation can give someone a second chance at life — connect with donors and hospitals instantly.</p>
            <div class="hero-cta-row">
                <a href="{{ route('user.register') }}" class="cta-primary"><i class="fas fa-tint"></i> Become a Donor</a>
                <a href="{{ route('user.login') }}" class="cta-secondary"><i class="fas fa-search"></i> Request Blood</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">{{ $totalUnits }}</div>
                    <div class="stat-label">Units Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">{{ $bloodStock->count() }}</div>
                    <div class="stat-label">Blood Types</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">24/7</div>
                    <div class="stat-label">Emergency Support</div>
                </div>
            </div>
        </div>

        <!-- Live Stock Card -->
        <div class="hero-card-wrap fade-up" style="transition-delay:.15s">
            <div class="hero-card">
                <div class="hero-card-title">Live Blood Stock</div>
                <div class="hero-card-sub">Real-time availability — updated every few minutes</div>
                <div class="blood-type-grid">
                    @foreach ($bloodStock as $stock)
                        @php
                            $qty = (int) $stock->quantity;
                            $cls = $qty > 5 ? 'hot' : ($qty > 0 ? 'low' : 'empty');
                            $unit = $qty === 1 ? 'unit' : 'units';
                        @endphp
                        <div class="blood-chip {{ $cls }}">
                            <div class="blood-chip-type">{{ $stock->blood_group }}</div>
                            <div class="blood-chip-units">{{ $qty }} {{ $unit }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="stock-bar-wrap">
                    <div class="stock-label-row">
                        <span>Overall stock level</span>
                        <span>{{ $totalUnits }} / {{ $maxUnits }} units</span>
                    </div>
                    <div class="stock-bar-track">
                        <div class="stock-bar-fill" style="width:{{ $stockPercentage }}%"></div>
                    </div>
                </div>
                <div class="hero-card-footer">
                    <div class="live-badge"><div class="live-dot"></div> Live data</div>
                    <a href="{{ route('user.login') }}" class="card-action-link">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features-section">
    <div class="section-inner">
        <div class="fade-up">
            <div class="section-label">What we offer</div>
            <h2 class="section-title">Everything you need<br>in one place</h2>
            <p class="section-desc">A complete platform connecting donors, hospitals, and patients with real-time tools and AI-powered matching.</p>
        </div>
        <div class="features-grid">
            <a href="{{ route('user.register') }}" class="feat-card fade-up" style="transition-delay:.05s">
                <div class="feat-icon"><i class="fas fa-tint"></i></div>
                <div class="feat-title">Donate Blood</div>
                <div class="feat-desc">Register as a verified donor and get matched with patients who need your blood type right now, in your area.</div>
                <div class="feat-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('user.login') }}" class="feat-card fade-up" style="transition-delay:.1s">
                <div class="feat-icon"><i class="fas fa-hospital"></i></div>
                <div class="feat-title">Request Blood</div>
                <div class="feat-desc">Submit urgent blood requests in seconds. Our system finds compatible donors nearby and notifies them instantly.</div>
                <div class="feat-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('user.login') }}" class="feat-card fade-up" style="transition-delay:.15s">
                <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="feat-title">Track Availability</div>
                <div class="feat-desc">Monitor live blood stock levels across all blood types. Administrators get full inventory control and analytics.</div>
                <div class="feat-arrow"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section">
    <div class="section-inner">
        <div class="fade-up">
            <div class="section-label">Process</div>
            <h2 class="section-title">How it works</h2>
            <p class="section-desc" style="color:rgba(253,248,243,.55)">Three simple steps from registration to life-saving donation.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card fade-up" style="transition-delay:.05s">
                <div class="step-num">01</div>
                <div class="step-title">Create Account</div>
                <div class="step-desc">Register in under two minutes. Provide your blood type, contact info, and location so we can match you accurately.</div>
            </div>
            <div class="step-card fade-up" style="transition-delay:.1s">
                <div class="step-num">02</div>
                <div class="step-title">Get Matched</div>
                <div class="step-desc">Our system automatically connects donors with nearby patients based on blood type compatibility and urgency.</div>
            </div>
            <div class="step-card fade-up" style="transition-delay:.15s">
                <div class="step-num">03</div>
                <div class="step-title">Save a Life</div>
                <div class="step-desc">Visit the hospital or donation center at the scheduled time. Track your donation impact in your personal dashboard.</div>
            </div>
        </div>
    </div>
</section>

<!-- URGENCY BANNER -->
<section class="urgency-section">
    <div class="urgency-inner fade-up">
        <div class="urgency-text">
            <div class="urgency-eyebrow">Emergency Appeal</div>
            <h2 class="urgency-title">{{ $criticalGroup }} blood critically<br>low — donate today</h2>
            <p class="urgency-sub">Universal donor blood is needed for trauma cases. Your donation can directly save lives within 24 hours.</p>
        </div>
        <a href="{{ route('user.register') }}" class="urgency-cta"><i class="fas fa-heart"></i> Register Now</a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        padding: 120px 28px 80px;
    }
    
    .hero-inner {
        max-width: 1180px;
        margin: 0 auto;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 480px;
        gap: 60px;
        align-items: center;
    }
    
    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(196,30,58,.08);
        border: 1px solid rgba(196,30,58,.18);
        border-radius: 999px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        color: var(--crimson);
        letter-spacing: .8px;
        text-transform: uppercase;
        margin-bottom: 28px;
    }
    
    .hero-eyebrow-dot {
        width: 7px;
        height: 7px;
        background: var(--crimson);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%,100% { opacity:1; transform:scale(1); }
        50% { opacity:.5; transform:scale(.7); }
    }
    
    .hero-title {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(52px,6vw,88px);
        line-height: 1.0;
        letter-spacing: -2px;
        color: var(--ink);
        margin-bottom: 24px;
    }
    
    .hero-title em { color: var(--crimson); font-style: italic; }
    
    .hero-sub {
        font-size: 18px;
        color: var(--ink-soft);
        max-width: 520px;
        line-height: 1.7;
        margin-bottom: 44px;
        font-weight: 300;
    }
    
    .hero-cta-row { display: flex; gap: 14px; flex-wrap: wrap; }
    
    .cta-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--crimson);
        color: white;
        padding: 16px 36px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 16px 40px rgba(196,30,58,.32);
        transition: all .24s;
    }
    
    .cta-primary:hover {
        background: var(--crimson-light);
        transform: translateY(-2px);
        color: white;
    }
    
    .cta-secondary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: transparent;
        color: var(--ink);
        padding: 16px 32px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        border: 1.5px solid rgba(24,16,14,.14);
        transition: all .24s;
    }
    
    .cta-secondary:hover {
        border-color: var(--crimson);
        color: var(--crimson);
        background: rgba(196,30,58,.04);
    }
    
    .hero-stats { display: flex; gap: 36px; margin-top: 56px; }
    
    .stat-num {
        font-family: 'Instrument Serif', serif;
        font-size: 36px;
        color: var(--crimson);
        line-height: 1;
    }
    
    .stat-label {
        font-size: 12px;
        color: var(--ink-soft);
        font-weight: 500;
        letter-spacing: .3px;
        margin-top: 4px;
    }
    
    .hero-card-wrap { position: relative; }
    
    .hero-card-wrap::before {
        content: '';
        position: absolute;
        inset: -20px;
        background: radial-gradient(circle, rgba(196,30,58,.12), transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }
    
    .hero-card {
        position: relative;
        z-index: 1;
        background: var(--card-bg);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(196,30,58,.12);
        border-radius: 32px;
        padding: 36px;
        box-shadow: var(--shadow-warm);
    }
    
    .hero-card-title {
        font-family: 'Instrument Serif', serif;
        font-size: 22px;
        color: var(--ink);
        margin-bottom: 6px;
    }
    
    .hero-card-sub {
        font-size: 13px;
        color: var(--ink-soft);
        margin-bottom: 22px;
    }
    
    .blood-type-grid {
        display: grid;
        grid-template-columns: repeat(4,1fr);
        gap: 10px;
        margin-bottom: 24px;
    }
    
    .blood-chip {
        background: var(--warm-gray);
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
        transition: all .2s;
        cursor: default;
    }
    
    .blood-chip:hover {
        background: rgba(196,30,58,.10);
        transform: translateY(-3px);
    }
    
    .blood-chip.hot { background: rgba(196,30,58,.08); border: 1.5px solid rgba(196,30,58,.22); }
    .blood-chip.low { background: rgba(217,119,6,.07); border: 1.5px solid rgba(217,119,6,.25); }
    .blood-chip.empty { background: rgba(196,30,58,.04); border: 1.5px dashed rgba(196,30,58,.20); opacity: .65; }
    
    .blood-chip-type {
        font-family: 'Instrument Serif', serif;
        font-size: 20px;
        color: var(--crimson);
        line-height: 1;
    }
    
    .blood-chip-units {
        font-size: 11px;
        color: var(--ink-soft);
        margin-top: 3px;
    }
    
    .stock-bar-wrap { margin-top: 6px; }
    
    .stock-label-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--ink-soft);
        margin-bottom: 8px;
    }
    
    .stock-bar-track {
        height: 6px;
        background: var(--warm-gray);
        border-radius: 999px;
        overflow: hidden;
    }
    
    .stock-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--crimson-light), var(--crimson));
        animation: fillBar .9s ease-out forwards;
    }
    
    @keyframes fillBar { from { width: 0; } }
    
    .hero-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(24,16,14,.07);
    }
    
    .live-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #16a34a;
        font-weight: 600;
    }
    
    .live-dot {
        width: 7px;
        height: 7px;
        background: #16a34a;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }
    
    .card-action-link {
        font-size: 13px;
        font-weight: 600;
        color: var(--crimson);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap .2s;
    }
    
    .card-action-link:hover { gap: 10px; color: var(--crimson); }
    
    /* Features Section */
    .features-section { padding: 100px 28px; position: relative; z-index: 1; }
    
    .section-inner { max-width: 1180px; margin: 0 auto; }
    
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--crimson);
        margin-bottom: 14px;
    }
    
    .section-title {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(36px,4vw,58px);
        letter-spacing: -1.5px;
        color: var(--ink);
        line-height: 1.1;
        margin-bottom: 20px;
    }
    
    .section-desc {
        font-size: 17px;
        color: var(--ink-soft);
        font-weight: 300;
        max-width: 540px;
        line-height: 1.7;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 24px;
        margin-top: 60px;
    }
    
    .feat-card {
        background: var(--card-bg);
        border: 1px solid rgba(24,16,14,.07);
        border-radius: 28px;
        padding: 36px 32px;
        transition: all .3s;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }
    
    .feat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(196,30,58,.06), transparent 60%);
        opacity: 0;
        transition: opacity .3s;
    }
    
    .feat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 32px 80px rgba(196,30,58,.12);
        border-color: rgba(196,30,58,.18);
    }
    
    .feat-card:hover::after { opacity: 1; }
    
    .feat-icon {
        width: 56px;
        height: 56px;
        background: rgba(196,30,58,.08);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--crimson);
        font-size: 22px;
        margin-bottom: 24px;
        transition: background .3s;
    }
    
    .feat-card:hover .feat-icon { background: rgba(196,30,58,.14); }
    
    .feat-title {
        font-family: 'Instrument Serif', serif;
        font-size: 24px;
        color: var(--ink);
        margin-bottom: 10px;
        letter-spacing: -.3px;
    }
    
    .feat-desc {
        font-size: 15px;
        color: var(--ink-soft);
        line-height: 1.65;
        font-weight: 300;
    }
    
    .feat-arrow {
        margin-top: 24px;
        color: var(--crimson);
        font-size: 20px;
        transition: transform .2s;
    }
    
    .feat-card:hover .feat-arrow { transform: translateX(6px); }
    
    /* How Section */
    .how-section {
        padding: 100px 28px;
        background: var(--ink);
        position: relative;
        overflow: hidden;
    }
    
    .how-section::before {
        content: '';
        position: absolute;
        top: -200px;
        right: -200px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(196,30,58,.15), transparent 70%);
        pointer-events: none;
    }
    
    .how-section .section-title { color: var(--cream); }
    .how-section .section-label { color: rgba(196,30,58,.9); }
    
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 24px;
        margin-top: 60px;
    }
    
    .step-card {
        border: 1px solid rgba(253,248,243,.08);
        border-radius: 28px;
        padding: 36px 32px;
        transition: border-color .3s;
    }
    
    .step-card:hover { border-color: rgba(196,30,58,.4); }
    
    .step-num {
        font-family: 'Instrument Serif', serif;
        font-size: 60px;
        color: rgba(196,30,58,.18);
        line-height: 1;
        margin-bottom: 16px;
        letter-spacing: -2px;
    }
    
    .step-title {
        font-family: 'Instrument Serif', serif;
        font-size: 22px;
        color: var(--cream);
        margin-bottom: 10px;
    }
    
    .step-desc {
        font-size: 15px;
        color: rgba(253,248,243,.55);
        line-height: 1.65;
        font-weight: 300;
    }
    
    /* Urgency Section */
    .urgency-section { padding: 80px 28px; position: relative; z-index: 1; }
    
    .urgency-inner {
        max-width: 1180px;
        margin: 0 auto;
        background: var(--crimson);
        border-radius: 36px;
        padding: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .urgency-inner::before {
        content: '';
        position: absolute;
        right: -80px;
        top: -80px;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    
    .urgency-inner::after {
        content: '';
        position: absolute;
        left: 30%;
        bottom: -120px;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    
    .urgency-text { flex: 1; }
    
    .urgency-eyebrow {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: rgba(255,255,255,.65);
        margin-bottom: 12px;
    }
    
    .urgency-title {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(32px,3.5vw,52px);
        color: white;
        line-height: 1.1;
        letter-spacing: -1px;
    }
    
    .urgency-sub {
        font-size: 16px;
        color: rgba(255,255,255,.7);
        margin-top: 14px;
        font-weight: 300;
    }
    
    .urgency-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: white;
        color: var(--crimson);
        padding: 18px 40px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all .24s;
    }
    
    .urgency-cta:hover {
        transform: scale(1.04);
        box-shadow: 0 20px 50px rgba(0,0,0,.2);
        color: var(--crimson);
    }
    
    @media (max-width: 900px) {
        .hero-inner { grid-template-columns: 1fr; }
        .hero-card-wrap { display: none; }
        .features-grid, .steps-grid { grid-template-columns: 1fr; }
        .urgency-inner { flex-direction: column; text-align: center; padding: 48px 32px; }
        .hero-stats { gap: 24px; }
    }
</style>
@endpush
