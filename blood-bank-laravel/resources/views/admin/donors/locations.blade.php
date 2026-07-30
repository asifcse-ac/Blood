@extends('layouts.admin')

@section('title', 'Donor Locations')

@section('content')
<div class="page-header">
    <div>
        <h2>Donor Locations</h2>
        <div class="page-header-sub">View donor locations on map</div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header">
        <i class="fas fa-map-location-dot"></i> Donor Map
    </div>
    <div class="dash-card-body p-4">
        @if (count($donors) > 0)
            <div id="map" style="height: 500px; background: #f0f0f0; border-radius: 10px; margin-bottom: 20px;">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt fa-2x mb-2 d-block"></i>
                        {{ count($donors) }} donors with location data
                    </p>
                </div>
            </div>
            
            <h5 class="mb-3">Donors with Location Data</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Blood Group</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donors as $donor)
                            <tr>
                                <td><strong>{{ $donor->full_name }}</strong></td>
                                <td><span class="blood-group-pill">{{ $donor->blood_group }}</span></td>
                                <td>{{ $donor->phone }}</td>
                                <td>
                                    @if ($donor->latitude && $donor->longitude)
                                        <small>{{ $donor->latitude }}, {{ $donor->longitude }}</small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($donor->last_location_update)
                                        {{ $donor->last_location_update->diffForHumans() }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">No donors with location data available</p>
                <p class="text-muted small">Donors need to update their location to appear on the map</p>
            </div>
        @endif
    </div>
</div>
@endsection
