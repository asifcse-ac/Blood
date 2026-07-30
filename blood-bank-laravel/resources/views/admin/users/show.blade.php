@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="page-header">
    <div>
        <h2>User Details</h2>
        <div class="page-header-sub">View user information</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-user"></i> User Information
            </div>
            <div class="dash-card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>User ID</strong></td>
                        <td>#{{ $user->user_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Full Name</strong></td>
                        <td>{{ $user->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Username</strong></td>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Blood Group</strong></td>
                        <td>
                            @if ($user->blood_group)
                                <span class="blood-group-pill">{{ $user->blood_group }}</span>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Address</strong></td>
                        <td>{{ $user->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if ($user->status === 'active')
                                <span class="badge badge-approved">Active</span>
                            @else
                                <span class="badge badge-rejected">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Registered</strong></td>
                        <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-notes-medical"></i> Health Information
            </div>
            <div class="dash-card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Smoker</strong></td>
                        <td>{{ $user->is_smoker === 'yes' ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Hepatitis</strong></td>
                        <td>{{ $user->has_hepatitis === 'yes' ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Medical Conditions</strong></td>
                        <td>{{ $user->medical_conditions ?? 'None reported' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Medical Certificate</strong></td>
                        <td>
                            @if ($user->medical_certificate)
                                <a href="{{ asset($user->medical_certificate) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> View
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-clipboard-list"></i> Blood Requests ({{ $user->bloodRequests->count() }})
            </div>
            <div class="dash-card-body">
                @if ($user->bloodRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                    <th>Hospital</th>
                                    <th>Urgency</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->bloodRequests as $req)
                                    <tr>
                                        <td>#{{ $req->request_id }}</td>
                                        <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                                        <td>{{ $req->units_requested }}</td>
                                        <td>{{ $req->hospital_name }}</td>
                                        <td>
                                            @php
                                                $urgencyClass = $req->urgency === 'Critical' ? 'danger' : ($req->urgency === 'Urgent' ? 'warning' : 'secondary');
                                            @endphp
                                            <span class="badge bg-{{ $urgencyClass }}">{{ $req->urgency }}</span>
                                        </td>
                                        <td><span class="badge badge-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                                        <td>{{ $req->request_date->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No blood requests from this user</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
