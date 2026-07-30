<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of donors.
     */
    public function index()
    {
        $donors = Donor::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        return view('admin.donors.create');
    }

    /**
     * Store a newly created donor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer|min:18|max:65',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'last_donation_date' => 'nullable|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Donor::create($request->all());

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor created successfully.');
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit(Donor $donor)
    {
        return view('admin.donors.edit', compact('donor'));
    }

    /**
     * Update the specified donor.
     */
    public function update(Request $request, Donor $donor)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer|min:18|max:65',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'last_donation_date' => 'nullable|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $donor->update($request->all());

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor updated successfully.');
    }

    /**
     * Remove the specified donor.
     */
    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor deleted successfully.');
    }

    /**
     * Toggle donor availability.
     */
    public function toggleAvailability(Donor $donor)
    {
        $donor->is_available = !$donor->is_available;
        $donor->last_location_update = now();
        $donor->save();

        return back()->with('success', 'Donor availability updated.');
    }

    /**
     * Show donor locations map.
     */
    public function locations()
    {
        $donors = Donor::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'active')
            ->get();
        
        return view('admin.donors.locations', compact('donors'));
    }
}
