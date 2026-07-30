<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Notification;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    /**
     * Display a listing of blood requests.
     */
    public function index(Request $request)
    {
        $query = BloodRequest::with('user');

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter by urgency
        if ($request->has('urgency') && in_array($request->urgency, ['Normal', 'Urgent', 'Critical'])) {
            $query->where('urgency', $request->urgency);
        }

        // Filter by blood group
        if ($request->has('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $requests = $query->orderBy('request_date', 'desc')->paginate(15);

        return view('admin.requests.index', compact('requests'));
    }

    /**
     * Display the specified blood request.
     */
    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load('user');
        return view('admin.requests.show', compact('bloodRequest'));
    }

    /**
     * Approve a blood request.
     */
    public function approve(Request $request, BloodRequest $bloodRequest)
    {
        // Check if already processed
        if (!$bloodRequest->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        // Check stock availability
        $stock = BloodStock::findByBloodGroup($bloodRequest->blood_group);
        if (!$stock || $stock->quantity < $bloodRequest->units_requested) {
            return back()->with('error', "Insufficient stock for {$bloodRequest->blood_group}. Available: {$stock->quantity} units.");
        }

        // Approve the request
        $bloodRequest->approve($request->admin_remarks);

        // Notify user
        Notification::createForUser(
            $bloodRequest->user_id,
            "Your blood request #{$bloodRequest->request_id} for {$bloodRequest->units_requested} unit(s) of {$bloodRequest->blood_group} has been approved."
        );

        return back()->with('success', 'Blood request approved successfully.');
    }

    /**
     * Reject a blood request.
     */
    public function reject(Request $request, BloodRequest $bloodRequest)
    {
        // Check if already processed
        if (!$bloodRequest->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        $bloodRequest->reject($request->admin_remarks);

        // Notify user
        Notification::createForUser(
            $bloodRequest->user_id,
            "Your blood request #{$bloodRequest->request_id} has been rejected. Reason: " . ($request->admin_remarks ?? 'Not specified')
        );

        return back()->with('success', 'Blood request rejected.');
    }

    /**
     * Get pending requests count for dashboard.
     */
    public static function getPendingCount(): int
    {
        return BloodRequest::where('status', 'pending')->count();
    }
}
