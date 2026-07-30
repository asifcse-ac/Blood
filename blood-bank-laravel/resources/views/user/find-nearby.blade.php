@extends('layouts.user')

@section('title', 'Find Nearby Donors')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-map-location-dot"></i> Find Nearby Donors</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select class="form-select" id="blood_group">
                    <option value="">All Blood Groups</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                        <option value="{{ $group }}">{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search Radius (km)</label>
                <input type="number" class="form-control" id="radius" value="50" min="1" max="100">
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="button" id="searchBtn" class="btn btn-info d-block w-100">
                    <i class="fas fa-location-crosshairs"></i> Find Donors Near Me
                </button>
            </div>
        </div>

        <div id="map" style="height: 400px; background: #f0f0f0; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
            <p class="text-muted"><i class="fas fa-map"></i> Click "Find Donors Near Me" to search</p>
        </div>

        <div id="donorResults" class="row"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchBtn').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const bloodGroup = document.getElementById('blood_group').value;
            const radius = document.getElementById('radius').value;

            fetch('{{ route('user.get-nearby-donors') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    blood_group: bloodGroup,
                    radius: radius
                })
            })
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('donorResults');
                
                if (data.success && data.donors.length > 0) {
                    let html = '';
                    data.donors.forEach(function(donor) {
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">${donor.full_name}</h6>
                                            <span class="badge bg-danger">${donor.blood_group}</span>
                                        </div>
                                        <p class="text-muted mb-1"><i class="fas fa-phone"></i> ${donor.phone}</p>
                                        <p class="text-muted mb-0"><i class="fas fa-route"></i> ${donor.distance_km} km away</p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="tel:${donor.phone}" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-phone"></i> Contact
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    resultsDiv.innerHTML = html;
                } else {
                    resultsDiv.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No donors found within the specified radius</p></div>';
                }

                document.getElementById('searchBtn').disabled = false;
                document.getElementById('searchBtn').innerHTML = '<i class="fas fa-location-crosshairs"></i> Find Donors Near Me';
            })
            .catch(error => {
                alert('Error searching for donors');
                document.getElementById('searchBtn').disabled = false;
                document.getElementById('searchBtn').innerHTML = '<i class="fas fa-location-crosshairs"></i> Find Donors Near Me';
            });
        }, function(error) {
            alert('Unable to get your location. Please enable location services.');
            document.getElementById('searchBtn').disabled = false;
            document.getElementById('searchBtn').innerHTML = '<i class="fas fa-location-crosshairs"></i> Find Donors Near Me';
        });
    });
</script>
@endpush
