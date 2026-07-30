<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index()
    {
        // Monthly requests summary
        $monthlyRequests = BloodRequest::select(
            DB::raw('MONTH(request_date) as month'),
            DB::raw('YEAR(request_date) as year'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
        )
            ->whereYear('request_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        // Blood group distribution
        $bloodGroupDistribution = Donor::select('blood_group', DB::raw('COUNT(*) as count'))
            ->where('status', 'active')
            ->groupBy('blood_group')
            ->orderBy('blood_group')
            ->get();

        // Stock levels
        $stockLevels = BloodStock::orderBy('blood_group')->get();

        // Request urgency distribution
        $urgencyDistribution = BloodRequest::select('urgency', DB::raw('COUNT(*) as count'))
            ->groupBy('urgency')
            ->get();

        // Recent activity
        $recentActivity = BloodRequest::with('user')
            ->orderBy('request_date', 'desc')
            ->limit(10)
            ->get();

        // Summary stats
        $stats = [
            'total_donors' => Donor::where('status', 'active')->count(),
            'total_users' => User::where('status', 'active')->count(),
            'total_requests' => BloodRequest::count(),
            'approved_requests' => BloodRequest::where('status', 'approved')->count(),
            'rejected_requests' => BloodRequest::where('status', 'rejected')->count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'total_units' => BloodStock::sum('quantity'),
        ];

        return view('admin.reports.index', compact(
            'monthlyRequests',
            'bloodGroupDistribution',
            'stockLevels',
            'urgencyDistribution',
            'recentActivity',
            'stats'
        ));
    }

    /**
     * Export blood requests report.
     */
    public function exportRequests(Request $request)
    {
        $query = BloodRequest::with('user');

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('request_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('request_date', '<=', $request->to_date);
        }

        $requests = $query->orderBy('request_date', 'desc')->get();

        // Generate CSV
        $filename = 'blood_requests_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Request ID', 'User', 'Blood Group', 'Units', 'Hospital', 'Urgency', 'Status', 'Date']);

            foreach ($requests as $req) {
                fputcsv($file, [
                    $req->request_id,
                    $req->user->full_name,
                    $req->blood_group,
                    $req->units_requested,
                    $req->hospital_name,
                    $req->urgency,
                    $req->status,
                    $req->request_date->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
