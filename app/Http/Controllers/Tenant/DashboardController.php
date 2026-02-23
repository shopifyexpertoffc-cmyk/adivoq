<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $stats = $this->getStats();
        $recentInvoices = Invoice::with('brand')->latest()->take(5)->get();
        $recentPayments = Payment::with('brand')->latest()->take(5)->get();
        $upcomingDeadlines = Campaign::where('status', 'in_progress')
            ->where('deliverable_date', '>=', now())
            ->where('deliverable_date', '<=', now()->addDays(7))
            ->orderBy('deliverable_date')
            ->take(5)
            ->get();
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->with('brand')
            ->take(5)
            ->get();

        return view('tenant.dashboard', compact(
            'stats',
            'recentInvoices',
            'recentPayments',
            'upcomingDeadlines',
            'overdueInvoices'
        ));
    }

    /**
     * Calculate stats
     */
    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth();

        // Current month revenue
        $currentMonthRevenue = Payment::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->where('status', 'completed')
            ->sum('amount');

        // Last month revenue
        $lastMonthRevenue = Payment::whereMonth('payment_date', $lastMonth->month)
            ->whereYear('payment_date', $lastMonth->year)
            ->where('status', 'completed')
            ->sum('amount');

        // Revenue change percentage
        $revenueChange = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Pending amount
        $pendingAmount = Invoice::whereIn('status', ['sent', 'viewed', 'partial'])
            ->sum('balance_amount');

        // Total clients (brands)
        $totalClients = Brand::where('is_active', true)->count();

        // Active campaigns
        $activeCampaigns = Campaign::whereIn('status', ['confirmed', 'in_progress'])->count();

        // This month invoices
        $invoicesThisMonth = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Total revenue (all time)
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        return [
            'current_month_revenue' => $currentMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'revenue_change' => $revenueChange,
            'pending_amount' => $pendingAmount,
            'total_clients' => $totalClients,
            'active_campaigns' => $activeCampaigns,
            'invoices_this_month' => $invoicesThisMonth,
            'total_revenue' => $totalRevenue,
        ];
    }
}