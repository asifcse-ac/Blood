<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\BloodStock;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of donors.
     */
    public function index(Request $request)
    {
        $query = Donor::where('status', 'active');

        // Filter by blood group
        if ($request->has('blood_group') && $request->blood_group) {
            $query->where('blood_group', $request->blood_group);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $donors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('user.donors', compact('donors'));
    }

    /**
     * Show find nearby donors page.
     */
    public function findNearby()
    {
        return view('user.find-nearby');
    }

    /**
     * Get nearby donors via AJAX.
     */
    public function getNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:100',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius ?? 50;
        $bloodGroup = $request->blood_group;

        $donors = Donor::where('status', 'active')
            ->where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($bloodGroup, function ($q) use ($bloodGroup) {
                $q->where('blood_group', $bloodGroup);
            })
            ->get()
            ->map(function ($donor) use ($lat, $lng) {
                $donor->distance_km = round($donor->distanceFrom($lat, $lng), 2);
                return $donor;
            })
            ->filter(function ($donor) use ($radius) {
                return $donor->distance_km <= $radius;
            })
            ->sortBy('distance_km')
            ->values();

        return response()->json([
            'success' => true,
            'donors' => $donors,
        ]);
    }

    /**
     * Show contact donor page.
     */
    public function contact(Donor $donor)
    {
        return view('user.contact-donor', compact('donor'));
    }

    /**
     * Track blood stock.
     */
    public function trackStock()
    {
        $bloodStock = BloodStock::orderBy('blood_group')->get();
        $totalUnits = $bloodStock->sum('quantity');

        return view('user.track-stock', compact('bloodStock', 'totalUnits'));
    }
}
