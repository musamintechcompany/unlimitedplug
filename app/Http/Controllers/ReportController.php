<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|uuid',
            'reason' => 'required|string',
            'details' => 'required|string',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
            'details' => $validated['details'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. We will review it shortly.'
        ]);
    }
}
