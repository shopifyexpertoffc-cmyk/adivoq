<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['causer', 'subject']);

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by causer type
        if ($request->filled('causer_type')) {
            $query->where('causer_type', $request->causer_type);
        }

        $logs = $query->latest()->paginate(30);

        $actions = ActivityLog::distinct()->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'actions'));
    }

    /**
     * Show log details
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['causer', 'subject']);
        return view('admin.activity-logs.show', compact('activityLog'));
    }
}