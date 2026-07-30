<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class BloodRequestController extends Controller
{
    /**
     * Show the form for creating a new blood request.
     */
    public function create()
    {
        $bloodStock = BloodStock::orderBy('blood_group')->get();
        return view('user.request-blood', compact('bloodStock'));
    }

    /**
     * Store a new blood request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_group'      => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_requested'  => 'required|integer|min:1',
            'hospital_name'    => 'required|string|max:200',
            'urgency'          => 'required|in:Normal,Urgent,Critical',
            'reason'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = auth('user')->user();

        // Check stock availability
        $stock = BloodStock::where('blood_group', $request->blood_group)->first();

        if (!$stock || $stock->quantity < $request->units_requested) {
            return back()
                ->with('error', "Insufficient stock for {$request->blood_group}. Available: " . ($stock->quantity ?? 0) . " units.")
                ->withInput();
        }

        BloodRequest::create([
            'user_id'          => $user->user_id,
            'blood_group'      => $request->blood_group,
            'units_requested'  => $request->units_requested,
            'reason'           => $request->reason,
            'hospital_name'    => $request->hospital_name,
            'urgency'          => $request->urgency,
        ]);

        return redirect()->route('user.requests.index')
            ->with('success', 'Blood request submitted successfully! Admin will review your request.');
    }

    /**
     * Display user's blood requests.
     */
    public function index()
    {
        $user = auth('user')->user();
        $requests = BloodRequest::where('user_id', $user->user_id)
            ->orderBy('request_date', 'desc')
            ->paginate(10);

        return view('user.my-requests', compact('requests'));
    }

    /**
     * Display the specified blood request.
     */
    public function show(BloodRequest $bloodRequest)
    {
        // Ensure user owns this request
        if ($bloodRequest->user_id !== auth('user')->user()->user_id) {
            abort(403);
        }

        return view('user.request-detail', compact('bloodRequest'));
    }
}
