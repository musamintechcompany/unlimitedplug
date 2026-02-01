<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('management.portal.admin.reports.index', compact('reports'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
        ]);

        $report->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Report status updated successfully.'
        ]);
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully.'
        ]);
    }
}
