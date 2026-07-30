<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodStock;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    /**
     * Display blood stock overview.
     */
    public function index()
    {
        $bloodStock = BloodStock::orderBy('blood_group')->get();
        $totalUnits = $bloodStock->sum('quantity');
        
        return view('admin.blood-stock.index', compact('bloodStock', 'totalUnits'));
    }

    /**
     * Update blood stock quantity.
     */
    public function update(Request $request, BloodStock $bloodStock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $bloodStock->quantity = $request->quantity;
        $bloodStock->last_updated = now();
        $bloodStock->save();

        return back()->with('success', 'Blood stock updated successfully.');
    }

    /**
     * Add units to blood stock.
     */
    public function addUnits(Request $request, BloodStock $bloodStock)
    {
        $request->validate([
            'units' => 'required|integer|min:1',
        ]);

        $bloodStock->increment('quantity', $request->units);
        $bloodStock->last_updated = now();
        $bloodStock->save();

        return back()->with('success', "Added {$request->units} units to {$bloodStock->blood_group}.");
    }

    /**
     * Remove units from blood stock.
     */
    public function removeUnits(Request $request, BloodStock $bloodStock)
    {
        $request->validate([
            'units' => 'required|integer|min:1',
        ]);

        if ($bloodStock->quantity < $request->units) {
            return back()->with('error', 'Insufficient stock.');
        }

        $bloodStock->decrement('quantity', $request->units);
        $bloodStock->last_updated = now();
        $bloodStock->save();

        return back()->with('success', "Removed {$request->units} units from {$bloodStock->blood_group}.");
    }
}
