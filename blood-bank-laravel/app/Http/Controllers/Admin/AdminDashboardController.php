<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'donors' => Donor::where('status', 'active')->count(),
            'users' => User::where('status', 'active')->count(),
            'pending' => BloodRequest::where('status', 'pending')->count(),
            'units' => BloodStock::sum('quantity'),
        ];

        $bloodStock = BloodStock::orderBy('blood_group')->get();
        $recentRequests = BloodRequest::with('user')
            ->orderBy('request_date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'bloodStock', 'recentRequests'));
    }
}
