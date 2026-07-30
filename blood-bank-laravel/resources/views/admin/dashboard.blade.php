@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <div class="page-header-sub">Welcome back, {{ auth('admin')->user()->full_name }} · {{ date('l, F j, Y') }}</div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-cards-row">
    <div class="sc sc-donors">
        <div class="sc-icon"><i class="fas fa-users"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['donors'] }}</span>
            <span class="sc-label">Active Donors</span>
        </div>
    </div>
    <div class="sc sc-units">
        <div class="sc-icon"><i class="fas fa-tint"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['units'] }}</span>
            <span class="sc-label">Total Units</span>
        </div>
    </div>
    <div class="sc sc-pending">
        <div class="sc-icon"><i class="fas fa-clock"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['pending'] }}</span>
            <span class="sc-label">Pending Requests</span>
        </div>
    </div>
    <div class="sc sc-users">
        <div class="sc-icon"><i class="fas fa-user-friends"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['users'] }}</span>
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
                        @foreach ($bloodStock as $stock)
                            @php
                                $qty = (int) $stock->quantity;
                                $badge = $qty > 5 ? ['Good','badge-good'] : ($qty > 0 ? ['Low','badge-low'] : ['Empty','badge-empty']);
                            @endphp
                            <tr>
                                <td><span class="blood-group-pill">{{ $stock->blood_group }}</span></td>
                                <td><strong>{{ $qty }}</strong></td>
                                <td><span class="badge {{ $badge[1] }}">{{ $badge[0] }}</span></td>
                            </tr>
                        @endforeach
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
                @if (count($recentRequests) > 0)
                    @foreach ($recentRequests as $req)
                        @php
                            $st = $req->status;
                            $badge_cls = $st === 'approved' ? 'badge-approved' : ($st === 'rejected' ? 'badge-rejected' : 'badge-pending');
                        @endphp
                        <div class="req-item">
                            <div>
                                <div class="req-name">{{ $req->user->full_name }}</div>
                                <div class="req-meta">
                                    <span class="blood-group-pill" style="font-size:12px;padding:2px 8px">{{ $req->blood_group }}</span>
                                    &nbsp;{{ $req->units_requested }} unit{{ $req->units_requested != 1 ? 's' : '' }}
                                    &nbsp;·&nbsp;{{ $req->request_date->format('M d, Y') }}
                                </div>
                            </div>
                            <span class="badge {{ $badge_cls }}">{{ ucfirst($st) }}</span>
                        </div>
                    @endforeach
                    <div style="padding:14px 20px; border-top:1px solid rgba(196,30,58,.06);">
                        <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-outline-danger">View All Requests</a>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.3"></i>
                        No recent requests
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
