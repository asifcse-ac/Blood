@extends('layouts.admin')

@section('title', 'Add New Donor')

@section('content')
<div class="page-header">
    <div>
        <h2>Add New Donor</h2>
        <div class="page-header-sub">Register a new blood donor</div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-body p-4">
        <form action="{{ route('admin.donors.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Blood Group *</label>
                    <select class="form-select" name="blood_group" required>
                        <option value="">Select Blood Group</option>
                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Age *</label>
                    <input type="number" class="form-control" name="age" min="18" max="65" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gender *</label>
                    <select class="form-select" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone *</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Donation Date</label>
                    <input type="date" class="form-control" name="last_donation_date">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" class="form-control" name="latitude">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" class="form-control" name="longitude">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-crime">
                    <i class="fas fa-save"></i> Save Donor
                </button>
                <a href="{{ route('admin.donors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
