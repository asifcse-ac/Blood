@extends('layouts.admin')

@section('title', 'Blood Requests')

@section('content')
<div class="page-header">
    <div>
        <h2>Blood Requests</h2>
        <div class="page-header-sub">Manage and process blood requests</div>
    </div>
</div>

<!-- Filters -->
<div class="dash-card mb-4">
    <div class="dash-card-body p-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Urgency</label>
                <select class="form-select" name="urgency">
                    <option value="">All Urgency</option>
                    <option value="Normal" {{ request('urgency') === 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Urgent" {{ request('urgency') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="Critical" {{ request('urgency') === 'Critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Blood Group</label>
                <select class="form-select" name="blood_group">
                    <option value="">All Blood Groups</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                        <option value="{{ $group }}" {{ request('blood_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-crime w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Requests Table -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fas fa-clipboard-list"></i> Blood Requests List
    </div>
    <div class="dash-card-body">
        @if (count($requests) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Hospital</th>
                            <th>Urgency</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr>
                                <td>#{{ $req->request_id }}</td>
                                <td>
                                    <strong>{{ $req->user->full_name }}</strong><br>
                                    <small class="text-muted">{{ $req->user->phone }}</small>
                                </td>
                                <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                                <td><strong>{{ $req->units_requested }}</strong></td>
                                <td>{{ $req->hospital_name }}</td>
                                <td>
                                    @php
                                        $urgencyClass = $req->urgency === 'Critical' ? 'danger' : ($req->urgency === 'Urgent' ? 'warning' : 'secondary');
                                    @endphp
                                    <span class="badge bg-{{ $urgencyClass }}">{{ $req->urgency }}</span>
                                </td>
                                <td><span class="badge badge-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                                <td>{{ $req->request_date->format('M d, Y') }}</td>
                                <td>
                                    @if ($req->status === 'pending')
                                        <form action="{{ route('admin.requests.approve', $req) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this request?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->request_id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">Processed</span>
                                    @endif
                                </td>
                            </tr>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $req->request_id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Request #{{ $req->request_id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.requests.reject', $req) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for Rejection</label>
                                                    <textarea class="form-control" name="admin_remarks" rows="3" placeholder="Enter reason..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Reject Request</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-3">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-3 d-block" style="opacity:.3"></i>
                No blood requests found
            </div>
        @endif
    </div>
</div>
@endsection
