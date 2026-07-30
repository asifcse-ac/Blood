@extends('layouts.user')

@section('title', 'My Blood Requests')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> My Blood Requests</h5>
    </div>
    <div class="card-body">
        @if (count($requests) > 0)
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
                        @foreach ($requests as $req)
                            <tr>
                                <td>#{{ $req->request_id }}</td>
                                <td><span class="badge bg-danger">{{ $req->blood_group }}</span></td>
                                <td>{{ $req->units_requested }}</td>
                                <td>{{ $req->hospital_name }}</td>
                                <td>
                                    @php
                                        $urgencyClass = $req->urgency === 'Critical' ? 'danger' : ($req->urgency === 'Urgent' ? 'warning' : 'secondary');
                                    @endphp
                                    <span class="badge bg-{{ $urgencyClass }}">{{ $req->urgency }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = $req->status === 'approved' ? 'success' : ($req->status === 'rejected' ? 'danger' : 'warning');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($req->status) }}</span>
                                </td>
                                <td>{{ $req->request_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $requests->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No blood requests yet</p>
                <a href="{{ route('user.request-blood') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Request Blood
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
