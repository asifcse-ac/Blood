<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page.
     */
    public function index()
    {
        // Get blood stock data
        $bloodStock = BloodStock::orderBy('blood_group')->get();
        
        // Calculate totals
        $totalUnits = $bloodStock->sum('quantity');
        $maxUnits = $bloodStock->count() * 20;
        $stockPercentage = $maxUnits > 0 ? min(100, round($totalUnits / $maxUnits * 100)) : 0;
        
        // Find critical blood type
        $critical = $bloodStock->firstWhere('quantity', 0) ?? $bloodStock->sortBy('quantity')->first();
        $criticalGroup = $critical ? $critical->blood_group : 'O-';

        return view('home', compact('bloodStock', 'totalUnits', 'maxUnits', 'stockPercentage', 'criticalGroup'));
    }
}
