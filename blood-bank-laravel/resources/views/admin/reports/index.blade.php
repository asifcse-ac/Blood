@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="page-header">
    <div>
        <h2>Reports & Analytics</h2>
        <div class="page-header-sub">View system statistics and generate reports</div>
    </div>
    <a href="{{ route('admin.reports.export') }}" class="btn btn-success">
        <i class="fas fa-download"></i> Export Requests
    </a>
</div>

<!-- Stats Cards -->
<div class="stat-cards-row">
    <div class="sc sc-donors">
        <div class="sc-icon"><i class="fas fa-users"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['total_donors'] }}</span>
            <span class="sc-label">Total Donors</span>
        </div>
    </div>
    <div class="sc sc-users">
        <div class="sc-icon"><i class="fas fa-user-friends"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['total_users'] }}</span>
            <span class="sc-label">Total Users</span>
        </div>
    </div>
    <div class="sc sc-units">
        <div class="sc-icon"><i class="fas fa-clipboard-list"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['total_requests'] }}</span>
            <span class="sc-label">Total Requests</span>
        </div>
    </div>
    <div class="sc sc-pending">
        <div class="sc-icon"><i class="fas fa-check-circle"></i></div>
        <div class="sc-body">
            <span class="sc-num">{{ $stats['approved_requests'] }}</span>
            <span class="sc-label">Approved</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Blood Group Distribution -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-chart-pie"></i> Donor Blood Group Distribution
            </div>
            <div class="dash-card-body p-4">
                @if (count($bloodGroupDistribution) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Blood Group</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalDonors = $bloodGroupDistribution->sum('count'); @endphp
                                @foreach ($bloodGroupDistribution as $item)
                                    <tr>
                                        <td><span class="blood-group-pill">{{ $item->blood_group }}</span></td>
                                        <td><strong>{{ $item->count }}</strong></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-danger" style="width: {{ $totalDonors > 0 ? round($item->count / $totalDonors * 100) : 0 }}%">
                                                    {{ $totalDonors > 0 ? round($item->count / $totalDonors * 100) : 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No donor data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Stock Levels -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-tint"></i> Current Stock Levels
            </div>
            <div class="dash-card-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Blood Group</th>
                                <th>Units</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockLevels as $stock)
                                @php
                                    $qty = (int) $stock->quantity;
                                    $badge = $qty > 5 ? ['Good','badge-good'] : ($qty > 0 ? ['Low','badge-low'] : ['Empty','badge-empty']);
                                @endphp
                                <tr>
                                    <td><span class="blood-group-pill">{{ $stock->blood_group }}</span></td>
                                    <td><strong>{{ $qty }}</strong> units</td>
                                    <td><span class="badge {{ $badge[1] }}">{{ $badge[0] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Status Overview -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-tasks"></i> Request Status Overview
            </div>
            <div class="dash-card-body p-4">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3">
                            <h2 class="text-warning">{{ $stats['pending_requests'] }}</h2>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3">
                            <h2 class="text-success">{{ $stats['approved_requests'] }}</h2>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3">
                            <h2 class="text-danger">{{ $stats['rejected_requests'] }}</h2>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Urgency Distribution -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-exclamation-triangle"></i> Request Urgency Distribution
            </div>
            <div class="dash-card-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Urgency Level</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($urgencyDistribution as $item)
                                <tr>
                                    <td>
                                        @if ($item->urgency === 'Critical')
                                            <span class="badge bg-danger">{{ $item->urgency }}</span>
                                        @elseif ($item->urgency === 'Urgent')
                                            <span class="badge bg-warning">{{ $item->urgency }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->urgency }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-history"></i> Recent Activity
            </div>
            <div class="dash-card-body">
                @if (count($recentActivity) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>User</th>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentActivity as $req)
                                    <tr>
                                        <td>#{{ $req->request_id }}</td>
                                        <td>{{ $req->user->full_name ?? 'N/A' }}</td>
                                        <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                                        <td>{{ $req->units_requested }}</td>
                                        <td>{{ $req->hospital_name }}</td>
                                        <td>
                                            @if ($req->status === 'approved')
                                                <span class="badge badge-approved">Approved</span>
                                            @elseif ($req->status === 'rejected')
                                                <span class="badge badge-rejected">Rejected</span>
                                            @else
                                                <span class="badge badge-pending">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $req->request_date->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
