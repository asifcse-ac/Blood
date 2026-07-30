@extends('layouts.user')

@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-bell"></i> My Notifications</h5>
        <form action="{{ route('user.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-light">Mark All as Read</button>
        </form>
    </div>
    <div class="card-body">
        @if (count($notifications) > 0)
            <div class="list-group">
                @foreach ($notifications as $notif)
                    <div class="list-group-item {{ $notif->is_read ? '' : 'list-group-item-info' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1">{{ $notif->message }}</p>
                                <small class="text-muted">{{ $notif->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            @if (!$notif->is_read)
                                <form action="{{ route('user.notifications.read', $notif) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark Read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $notifications->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No notifications</p>
            </div>
        @endif
    </div>
</div>
@endsection
