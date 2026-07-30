@extends('layouts.admin')

@section('title', 'Request Details')

@section('content')
<div class="page-header">
    <div>
        <h2>Blood Request #{{ $bloodRequest->request_id }}</h2>
        <div class="page-header-sub">View request details</div>
    </div>
    <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Requests
    </a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-clipboard-list"></i> Request Information
            </div>
            <div class="dash-card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Request ID</strong></td>
                        <td>#{{ $bloodRequest->request_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Blood Group</strong></td>
                        <td><span class="blood-group-pill">{{ $bloodRequest->blood_group }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Units Requested</strong></td>
                        <td><strong>{{ $bloodRequest->units_requested }}</strong> unit(s)</td>
                    </tr>
                    <tr>
                        <td><strong>Hospital</strong></td>
                        <td>{{ $bloodRequest->hospital_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Urgency</strong></td>
                        <td>
                            @php
                                $urgencyClass = $bloodRequest->urgency === 'Critical' ? 'danger' : ($bloodRequest->urgency === 'Urgent' ? 'warning' : 'secondary');
                            @endphp
                            <span class="badge bg-{{ $urgencyClass }}">{{ $bloodRequest->urgency }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if ($bloodRequest->status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif ($bloodRequest->status === 'rejected')
                                <span class="badge badge-rejected">Rejected</span>
                            @else
                                <span class="badge badge-pending">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Request Date</strong></td>
                        <td>{{ $bloodRequest->request_date->format('M d, Y H:i') }}</td>
                    </tr>
                    @if ($bloodRequest->processed_date)
                        <tr>
                            <td><strong>Processed Date</strong></td>
                            <td>{{ $bloodRequest->processed_date->format('M d, Y H:i') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-user"></i> Requester Information
            </div>
            <div class="dash-card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Name</strong></td>
                        <td>{{ $bloodRequest->user->full_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $bloodRequest->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td>{{ $bloodRequest->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Blood Group</strong></td>
                        <td>{{ $bloodRequest->user->blood_group ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-file-alt"></i> Reason / Additional Information
            </div>
            <div class="dash-card-body p-4">
                <p>{{ $bloodRequest->reason ?? 'No reason provided' }}</p>
            </div>
        </div>
    </div>

    @if ($bloodRequest->admin_remarks)
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-header">
                    <i class="fas fa-comment"></i> Admin Remarks
                </div>
                <div class="dash-card-body p-4">
                    <p>{{ $bloodRequest->admin_remarks }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($bloodRequest->status === 'pending')
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-body p-4">
                    <div class="d-flex gap-3">
                        <form action="{{ route('admin.requests.approve', $bloodRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this request?')">
                                <i class="fas fa-check"></i> Approve Request
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Reject Request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request #{{ $bloodRequest->request_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.requests.reject', $bloodRequest) }}" method="POST">
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
    @endif
</div>
@endsection
