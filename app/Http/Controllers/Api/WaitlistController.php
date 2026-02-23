<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WaitlistController extends Controller
{
    /**
     * Store a new waitlist entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:waitlist,email',
            'phone' => 'required|string|max:20',
            'creator_type' => 'nullable|string|max:50',
            'followers' => 'nullable|string|max:50',
            'monthly_invoices' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:50',
        ]);

        // Generate unique ID
        $validated['uid'] = 'CP' . time() . Str::random(6);
        
        // Get position
        $position = Waitlist::count() + 1;
        $validated['position'] = $position;
        
        // Add IP and user agent
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        $waitlist = Waitlist::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the waitlist!',
            'data' => [
                'id' => $waitlist->uid,
                'position' => $position,
                'totalSignups' => $position,
            ]
        ]);
    }

    /**
     * Get waitlist count.
     */
    public function count()
    {
        return response()->json([
            'success' => true,
            'count' => Waitlist::count(),
        ]);
    }
}