@extends('layouts.user')

@section('title', 'Track Blood Stock')

@section('content')
<div class="hero-section text-center">
    <h1><i class="fas fa-chart-line"></i> Blood Stock Tracker</h1>
    <p class="lead">Real-time blood availability across all blood types</p>
    <h3>Total Available: <span class="badge bg-danger">{{ $totalUnits }} Units</span></h3>
</div>

<div class="row mb-4">
    @foreach ($bloodStock as $stock)
        @php
            $qty = (int) $stock->quantity;
            $statusClass = $qty > 5 ? 'success' : ($qty > 0 ? 'warning' : 'danger');
            $statusText = $qty > 5 ? 'Available' : ($qty > 0 ? 'Limited' : 'Unavailable');
        @endphp
        <div class="col-md-3">
            <div class="blood-card">
                <div class="blood-group-badge">{{ $stock->blood_group }}</div>
                <hr>
                <h4 class="text-{{ $statusClass }}">{{ $qty }} Units</h4>
                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
            </div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Blood Type Information</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Blood Type</th>
                        <th>Can Donate To</th>
                        <th>Can Receive From</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>O-</strong></td><td>All Blood Types</td><td>O-</td></tr>
                    <tr><td><strong>O+</strong></td><td>O+, A+, B+, AB+</td><td>O+, O-</td></tr>
                    <tr><td><strong>A-</strong></td><td>A+, AB+</td><td>A-, O-</td></tr>
                    <tr><td><strong>A+</strong></td><td>A+, AB+</td><td>A+, A-, O+, O-</td></tr>
                    <tr><td><strong>B-</strong></td><td>B+, AB+</td><td>B-, O-</td></tr>
                    <tr><td><strong>B+</strong></td><td>B+, AB+</td><td>B+, B-, O+, O-</td></tr>
                    <tr><td><strong>AB-</strong></td><td>AB+</td><td>AB-, A-, B-, O-</td></tr>
                    <tr><td><strong>AB+</strong></td><td>AB+</td><td>All Blood Types</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
