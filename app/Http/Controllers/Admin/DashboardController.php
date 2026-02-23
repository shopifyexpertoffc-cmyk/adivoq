<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $stats = $this->getStats();
        $recentTenants = Tenant::latest()->take(5)->get();
        $recentWaitlist = Waitlist::latest()->take(5)->get();
        $recentEnquiries = ContactEnquiry::new()->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentTenants',
            'recentWaitlist',
            'recentEnquiries'
        ));
    }

    /**
     * Get dashboard stats
     */
    public function stats()
    {
        return response()->json($this->getStats());
    }

    /**
     * Calculate statistics
     */
    protected function getStats(): array
    {
        return [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'trial_tenants' => Tenant::where('status', 'active')
                ->whereNotNull('trial_ends_at')
                ->where('trial_ends_at', '>', now())
                ->count(),
            'waitlist_count' => Waitlist::count(),
            'pending_waitlist' => Waitlist::pending()->count(),
            'new_enquiries' => ContactEnquiry::new()->count(),
            'monthly_revenue' => Subscription::active()
                ->where('billing_cycle', 'monthly')
                ->sum('amount'),
            'tenants_this_month' => Tenant::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'tenants_by_plan' => Tenant::select('plan', DB::raw('count(*) as count'))
                ->groupBy('plan')
                ->pluck('count', 'plan')
                ->toArray(),
        ];
    }
}