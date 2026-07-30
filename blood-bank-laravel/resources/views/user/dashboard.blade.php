@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="hero-section text-center">
    <h1><i class="fas fa-tint"></i> Welcome, {{ auth('user')->user()->full_name }}!</h1>
    <p class="lead">Manage blood requests and view availability</p>
    @if (auth('user')->user()->blood_group)
        <h3>Your Blood Group: <span class="badge bg-danger">{{ auth('user')->user()->blood_group }}</span></h3>
    @endif
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3 class="mb-3"><i class="fas fa-tint"></i> Blood Availability</h3>
    </div>
    @foreach ($bloodStock as $stock)
        @php
            $statusClass = $stock->quantity > 5 ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger');
            $statusText = $stock->quantity > 5 ? 'Available' : ($stock->quantity > 0 ? 'Limited' : 'Unavailable');
        @endphp
        <div class="col-md-3">
            <div class="blood-card">
                <div class="blood-group-badge">{{ $stock->blood_group }}</div>
                <hr>
                <h4 class="text-{{ $statusClass }}">{{ $stock->quantity }} Units</h4>
                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> My Recent Requests</h5>
            </div>
            <div class="card-body">
                @if (count($myRequests) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myRequests as $req)
                                    <tr>
                                        <td>{{ $req->request_date->format('M d, Y') }}</td>
                                        <td><span class="badge bg-danger">{{ $req->blood_group }}</span></td>
                                        <td>{{ $req->units_requested }}</td>
                                        <td>{{ $req->hospital_name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $req->getStatusBadgeClass() }}">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('user.requests.index') }}" class="btn btn-sm btn-primary">View All Requests</a>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No requests yet</p>
                        <a href="{{ route('user.request-blood') }}" class="btn btn-primary">Request Blood Now</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Active Donors</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach ($donors as $donor)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $donor->full_name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-tint text-danger"></i> {{ $donor->blood_group }}
                                    </small>
                                </div>
                                <span class="badge bg-danger">{{ $donor->blood_group }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('user.donors') }}" class="btn btn-sm btn-success mt-3 w-100">View All Donors</a>
            </div>
        </div>
    </div>
</div>
@endsection
