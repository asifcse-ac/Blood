@extends('layouts.admin')

@section('title', 'Edit Donor')

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Donor</h2>
        <div class="page-header-sub">Update donor information</div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-body p-4">
        <form action="{{ route('admin.donors.update', $donor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" value="{{ $donor->full_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Blood Group *</label>
                    <select class="form-select" name="blood_group" required>
                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <option value="{{ $group }}" {{ $donor->blood_group === $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Age *</label>
                    <input type="number" class="form-control" name="age" min="18" max="65" value="{{ $donor->age }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gender *</label>
                    <select class="form-select" name="gender" required>
                        @foreach (['Male', 'Female', 'Other'] as $gender)
                            <option value="{{ $gender }}" {{ $donor->gender === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone *</label>
                    <input type="text" class="form-control" name="phone" value="{{ $donor->phone }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $donor->email }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Donation Date</label>
                    <input type="date" class="form-control" name="last_donation_date" value="{{ $donor->last_donation_date?->format('Y-m-d') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2">{{ $donor->address }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" class="form-control" name="latitude" value="{{ $donor->latitude }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" class="form-control" name="longitude" value="{{ $donor->longitude }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status *</label>
                    <select class="form-select" name="status" required>
                        <option value="active" {{ $donor->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $donor->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-crime">
                    <i class="fas fa-save"></i> Update Donor
                </button>
                <a href="{{ route('admin.donors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
