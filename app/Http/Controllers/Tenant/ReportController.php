<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Monthly revenue data
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereYear('payment_date', $year)
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[] = $monthlyRevenue[$i] ?? 0;
        }

        // Top brands by revenue
        $topBrands = Brand::withSum(['invoices' => function ($q) use ($year) {
            $q->where('status', 'paid')->whereYear('paid_at', $year);
        }], 'total_amount')
            ->orderByDesc('invoices_sum_total_amount')
            ->take(5)
            ->get();

        // Revenue by platform
        $platformRevenue = Campaign::whereYear('created_at', $year)
            ->whereNotNull('platform')
            ->selectRaw('platform, SUM(paid_amount) as total')
            ->groupBy('platform')
            ->orderByDesc('total')
            ->get();

        // Quick stats
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->whereYear('payment_date', $year)->sum('amount'),
            'total_invoiced' => Invoice::whereYear('invoice_date', $year)->sum('total_amount'),
            'avg_invoice' => Invoice::whereYear('invoice_date', $year)->avg('total_amount') ?? 0,
            'total_campaigns' => Campaign::whereYear('created_at', $year)->count(),
            'completed_campaigns' => Campaign::whereYear('created_at', $year)->where('status', 'completed')->count(),
        ];

        return view('tenant.reports.index', compact('revenueData', 'topBrands', 'platformRevenue', 'stats', 'year'));
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $payments = Payment::with(['brand', 'invoice'])
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->orderBy('payment_date', 'desc')
            ->get();

        $summary = [
            'total' => $payments->sum('amount'),
            'count' => $payments->count(),
            'by_method' => $payments->groupBy('payment_method')->map->sum('amount'),
        ];

        return view('tenant.reports.revenue', compact('payments', 'summary', 'fromDate', 'toDate'));
    }

    /**
     * Tax report
     */
    public function tax(Request $request)
    {
        $year = $request->input('year', now()->year);
        $quarter = $request->input('quarter');

        $query = Invoice::where('status', 'paid')
            ->whereYear('invoice_date', $year);

        if ($quarter) {
            $query->whereRaw('QUARTER(invoice_date) = ?', [$quarter]);
        }

        $invoices = $query->with('brand')->orderBy('invoice_date')->get();

        $summary = [
            'total_revenue' => $invoices->sum('subtotal'),
            'total_tax' => $invoices->sum('tax_amount'),
            'total_tds' => $invoices->sum('tds_amount'),
            'gst_collected' => $invoices->where('tax_type', 'gst')->sum('tax_amount'),
            'net_revenue' => $invoices->sum('total_amount'),
        ];

        return view('tenant.reports.tax', compact('invoices', 'summary', 'year', 'quarter'));
    }

    /**
     * Clients report
     */
    public function clients(Request $request)
    {
        $brands = Brand::withCount(['campaigns', 'invoices'])
            ->withSum(['invoices' => function ($q) {
                $q->where('status', 'paid');
            }], 'total_amount')
            ->withSum(['invoices' => function ($q) {
                $q->whereIn('status', ['sent', 'viewed', 'partial']);
            }], 'balance_amount')
            ->orderByDesc('invoices_sum_total_amount')
            ->paginate(20);

        return view('tenant.reports.clients', compact('brands'));
    }

    /**
     * Export report
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'payments');
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $filename = "{$type}_report_{$fromDate}_to_{$toDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        if ($type === 'payments') {
            $data = Payment::with(['brand', 'invoice'])
                ->where('status', 'completed')
                ->whereBetween('payment_date', [$fromDate, $toDate])
                ->orderBy('payment_date')
                ->get();

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Date', 'Brand', 'Invoice', 'Amount', 'Method', 'Transaction ID']);

                foreach ($data as $payment) {
                    fputcsv($file, [
                        $payment->payment_date->format('Y-m-d'),
                        $payment->brand?->name ?? '-',
                        $payment->invoice?->invoice_number ?? '-',
                        $payment->amount,
                        $payment->payment_method ?? '-',
                        $payment->transaction_id ?? '-',
                    ]);
                }

                fclose($file);
            };
        } else {
            $data = Invoice::with('brand')
                ->whereBetween('invoice_date', [$fromDate, $toDate])
                ->orderBy('invoice_date')
                ->get();

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Invoice #', 'Date', 'Brand', 'Subtotal', 'Tax', 'TDS', 'Total', 'Paid', 'Status']);

                foreach ($data as $invoice) {
                    fputcsv($file, [
                        $invoice->invoice_number,
                        $invoice->invoice_date->format('Y-m-d'),
                        $invoice->brand->name,
                        $invoice->subtotal,
                        $invoice->tax_amount,
                        $invoice->tds_amount,
                        $invoice->total_amount,
                        $invoice->paid_amount,
                        $invoice->status,
                    ]);
                }

                fclose($file);
            };
        }

        return response()->stream($callback, 200, $headers);
    }
}