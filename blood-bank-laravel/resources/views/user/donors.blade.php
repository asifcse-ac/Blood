@extends('layouts.user')

@section('title', 'Donors Directory')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-users"></i> Active Donors</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select class="form-select" name="blood_group">
                    <option value="">All Blood Groups</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                        <option value="{{ $group }}" {{ request('blood_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search by name or location" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('user.donors') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        @if (count($donors) > 0)
            <div class="row">
                @foreach ($donors as $donor)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $donor->full_name }}</h5>
                                    <span class="badge bg-danger">{{ $donor->blood_group }}</span>
                                </div>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-phone"></i> {{ $donor->phone }}
                                </p>
                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt"></i> {{ $donor->address ?? 'Not specified' }}
                                </p>
                                <p class="card-text text-muted mb-0">
                                    <i class="fas fa-calendar"></i> Age: {{ $donor->age }} | {{ $donor->gender }}
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="tel:{{ $donor->phone }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-phone"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $donors->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No donors found</p>
            </div>
        @endif
    </div>
</div>
@endsection
