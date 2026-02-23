<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = Waitlist::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by creator type
        if ($request->filled('creator_type')) {
            $query->where('creator_type', $request->creator_type);
        }

        $waitlist = $query->latest()->paginate(20);

        $stats = [
            'total' => Waitlist::count(),
            'pending' => Waitlist::pending()->count(),
            'invited' => Waitlist::invited()->count(),
            'registered' => Waitlist::where('status', 'registered')->count(),
        ];

        return view('admin.waitlist.index', compact('waitlist', 'stats'));
    }

    /**
     * Show details
     */
    public function show(Waitlist $waitlist)
    {
        return view('admin.waitlist.show', compact('waitlist'));
    }

    /**
     * Delete entry
     */
    public function destroy(Waitlist $waitlist)
    {
        ActivityLog::log('deleted', 'Waitlist entry deleted: ' . $waitlist->email, null, auth('admin')->user());

        $waitlist->delete();

        return back()->with('success', 'Entry deleted successfully.');
    }

    /**
     * Send invite
     */
    public function invite(Waitlist $waitlist)
    {
        $waitlist->update([
            'status' => 'invited',
            'invited_at' => now(),
        ]);

        // TODO: Send invitation email

        ActivityLog::log('invited', 'Waitlist invite sent: ' . $waitlist->email, $waitlist, auth('admin')->user());

        return back()->with('success', 'Invitation sent successfully.');
    }

    /**
     * Export waitlist
     */
    public function export(Request $request)
    {
        $waitlist = Waitlist::all();

        $filename = 'waitlist_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($waitlist) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Creator Type', 'Followers', 'Monthly Invoices', 'Status', 'Position', 'Date']);

            foreach ($waitlist as $entry) {
                fputcsv($file, [
                    $entry->uid,
                    $entry->name,
                    $entry->email,
                    $entry->phone,
                    $entry->creator_type,
                    $entry->followers,
                    $entry->monthly_invoices,
                    $entry->status,
                    $entry->position,
                    $entry->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}