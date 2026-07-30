<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BloodStock;
use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = auth('user')->user();

        // Get blood stock data
        $bloodStock = BloodStock::orderBy('blood_group')->get();

        // Get user's recent requests
        $myRequests = BloodRequest::where('user_id', $user->user_id)
            ->orderBy('request_date', 'desc')
            ->limit(5)
            ->get();

        // Get some active donors
        $donors = Donor::where('status', 'active')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('user.dashboard', compact('bloodStock', 'myRequests', 'donors'));
    }
}
