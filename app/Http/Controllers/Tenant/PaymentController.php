<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Brand;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'brand', 'campaign']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->latest('payment_date')->paginate(20);
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        // Stats
        $stats = [
            'total_received' => Payment::where('status', 'completed')->sum('amount'),
            'this_month' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'last_month' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->subMonth()->month)
                ->whereYear('payment_date', now()->subMonth()->year)
                ->sum('amount'),
        ];

        return view('tenant.payments.index', compact('payments', 'brands', 'stats'));
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'brand', 'campaign']);

        return view('tenant.payments.show', compact('payment'));
    }

    /**
     * Delete payment
     */
    public function destroy(Payment $payment)
    {
        // Revert invoice amounts
        if ($payment->invoice) {
            $invoice = $payment->invoice;
            $newPaidAmount = $invoice->paid_amount - $payment->amount;
            $newBalance = $invoice->total_amount - $newPaidAmount;

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'balance_amount' => $newBalance,
                'status' => $newPaidAmount <= 0 ? 'sent' : 'partial',
                'paid_at' => null,
            ]);
        }

        // Revert campaign amounts
        if ($payment->campaign) {
            $campaign = $payment->campaign;
            $newPaidAmount = $campaign->paid_amount - $payment->amount;
            $campaign->update([
                'paid_amount' => $newPaidAmount,
                'payment_status' => $newPaidAmount <= 0 ? 'pending' : 'partial',
            ]);
        }

        $payment->delete();

        return back()->with('success', 'Payment deleted and amounts reverted.');
    }
}