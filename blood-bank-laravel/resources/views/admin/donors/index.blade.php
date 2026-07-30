@extends('layouts.admin')

@section('title', 'Manage Donors')

@section('content')
<div class="page-header">
    <div>
        <h2>Manage Donors</h2>
        <div class="page-header-sub">View and manage all registered blood donors</div>
    </div>
    <a href="{{ route('admin.donors.create') }}" class="btn btn-crime">
        <i class="fas fa-plus"></i> Add New Donor
    </a>
</div>

<div class="dash-card">
    <div class="dash-card-body">
        @if (count($donors) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Blood Group</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donors as $donor)
                            <tr>
                                <td>#{{ $donor->donor_id }}</td>
                                <td><strong>{{ $donor->full_name }}</strong></td>
                                <td><span class="blood-group-pill">{{ $donor->blood_group }}</span></td>
                                <td>{{ $donor->age }}</td>
                                <td>{{ $donor->gender }}</td>
                                <td>{{ $donor->phone }}</td>
                                <td>{{ $donor->address ?? 'N/A' }}</td>
                                <td>
                                    @if ($donor->status === 'active')
                                        <span class="badge badge-approved">Active</span>
                                    @else
                                        <span class="badge badge-rejected">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.donors.edit', $donor) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.donors.destroy', $donor) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this donor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $donors->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-2x mb-3 d-block" style="opacity:.3"></i>
                No donors found
            </div>
        @endif
    </div>
</div>
@endsection
