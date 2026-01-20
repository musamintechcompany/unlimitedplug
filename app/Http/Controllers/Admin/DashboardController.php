<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 30);
        
        // Handle custom date range
        if ($period === 'custom') {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            if ($startDate && $endDate) {
                // Custom date range is handled in the widget
                return view('management.portal.admin.dashboard', compact('period'));
            } else {
                // If no dates provided, default to 30 days
                $period = 30;
            }
        }
        
        return view('management.portal.admin.dashboard', compact('period'));
    }

    public function profile()
    {
        return view('management.portal.admin.profile');
    }
}
