<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\Milestone;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = Invoice::with('brand');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('invoice_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('invoice_date', '<=', $request->to_date);
        }

        $invoices = $query->latest()->paginate(15);
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        // Stats
        $stats = [
            'total_invoiced' => Invoice::sum('total_amount'),
            'total_paid' => Invoice::where('status', 'paid')->sum('total_amount'),
            'total_pending' => Invoice::whereIn('status', ['sent', 'viewed', 'partial'])->sum('balance_amount'),
            'overdue_count' => Invoice::where('status', '!=', 'paid')->where('due_date', '<', now())->count(),
        ];

        return view('tenant.invoices.index', compact('invoices', 'brands', 'stats'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $campaigns = collect();
        $milestone = null;

        if ($request->filled('brand_id')) {
            $campaigns = Campaign::where('brand_id', $request->brand_id)
                ->whereIn('status', ['confirmed', 'in_progress', 'delivered'])
                ->orderBy('name')
                ->get();
        }

        if ($request->filled('milestone_id')) {
            $milestone = Milestone::with('campaign.brand')->find($request->milestone_id);
        }

        $invoiceNumber = Invoice::generateInvoiceNumber();
        $tenant = tenant();

        return view('tenant.invoices.create', compact(
            'brands', 
            'campaigns', 
            'milestone', 
            'invoiceNumber',
            'tenant'
        ));
    }

    /**
     * Store new invoice
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'currency' => 'required|string|size:3',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'client_gst' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'tax_enabled' => 'boolean',
            'tax_type' => 'nullable|string|max:10',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tds_applicable' => 'boolean',
            'tds_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:2000',
            'status' => 'required|in:draft,sent',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as &$item) {
            $item['amount'] = $item['quantity'] * $item['rate'];
            $subtotal += $item['amount'];
        }

        // Calculate discount
        $discount = $validated['discount'] ?? 0;
        if ($validated['discount_type'] === 'percent') {
            $discount = ($subtotal * $discount) / 100;
        }

        // Calculate tax
        $taxableAmount = $subtotal - $discount;
        $taxAmount = 0;
        if ($validated['tax_enabled'] ?? false) {
            $taxAmount = ($taxableAmount * ($validated['tax_rate'] ?? 0)) / 100;
        }

        // Calculate TDS
        $tdsAmount = 0;
        if ($validated['tds_applicable'] ?? false) {
            $tdsAmount = ($taxableAmount * ($validated['tds_rate'] ?? 0)) / 100;
        }

        // Total
        $totalAmount = $taxableAmount + $taxAmount - $tdsAmount;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'brand_id' => $validated['brand_id'],
            'campaign_id' => $validated['campaign_id'],
            'milestone_id' => $validated['milestone_id'],
            'user_id' => auth()->id(),
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'currency' => $validated['currency'],
            'subtotal' => $subtotal,
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'],
            'tax_enabled' => $validated['tax_enabled'] ?? false,
            'tax_type' => $validated['tax_type'] ?? 'gst',
            'tax_rate' => $validated['tax_rate'] ?? 0,
            'tax_amount' => $taxAmount,
            'gst_number' => tenant('gst_number'),
            'tds_applicable' => $validated['tds_applicable'] ?? false,
            'tds_rate' => $validated['tds_rate'] ?? 0,
            'tds_amount' => $tdsAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance_amount' => $totalAmount,
            'status' => $validated['status'],
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'client_gst' => $validated['client_gst'],
            'items' => $validated['items'],
            'notes' => $validated['notes'],
            'terms' => $validated['terms'],
            'sent_at' => $validated['status'] === 'sent' ? now() : null,
        ]);

        // Update milestone if linked
        if ($invoice->milestone_id) {
            $invoice->milestone->update(['status' => 'submitted']);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Show invoice details
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['brand', 'campaign', 'milestone', 'payments']);

        return view('tenant.invoices.show', compact('invoice'));
    }

    /**
     * Show edit form
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot edit a paid invoice.');
        }

        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $campaigns = Campaign::where('brand_id', $invoice->brand_id)->get();
        $tenant = tenant();

        return view('tenant.invoices.edit', compact('invoice', 'brands', 'campaigns', 'tenant'));
    }

    /**
     * Update invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot edit a paid invoice.');
        }

        $validated = $request->validate([
            'due_date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'client_gst' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percent',
            'tax_enabled' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tds_applicable' => 'boolean',
            'tds_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:2000',
        ]);

        // Recalculate totals
        $subtotal = 0;
        foreach ($validated['items'] as &$item) {
            $item['amount'] = $item['quantity'] * $item['rate'];
            $subtotal += $item['amount'];
        }

        $discount = $validated['discount'] ?? 0;
        if ($validated['discount_type'] === 'percent') {
            $discount = ($subtotal * $discount) / 100;
        }

        $taxableAmount = $subtotal - $discount;
        $taxAmount = 0;
        if ($validated['tax_enabled'] ?? false) {
            $taxAmount = ($taxableAmount * ($validated['tax_rate'] ?? 0)) / 100;
        }

        $tdsAmount = 0;
        if ($validated['tds_applicable'] ?? false) {
            $tdsAmount = ($taxableAmount * ($validated['tds_rate'] ?? 0)) / 100;
        }

        $totalAmount = $taxableAmount + $taxAmount - $tdsAmount;

        $invoice->update([
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'],
            'tax_enabled' => $validated['tax_enabled'] ?? false,
            'tax_rate' => $validated['tax_rate'] ?? 0,
            'tax_amount' => $taxAmount,
            'tds_applicable' => $validated['tds_applicable'] ?? false,
            'tds_rate' => $validated['tds_rate'] ?? 0,
            'tds_amount' => $tdsAmount,
            'total_amount' => $totalAmount,
            'balance_amount' => $totalAmount - $invoice->paid_amount,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_address' => $validated['client_address'],
            'client_gst' => $validated['client_gst'],
            'items' => $validated['items'],
            'notes' => $validated['notes'],
            'terms' => $validated['terms'],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Delete invoice
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot delete a paid invoice.');
        }

        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Cannot delete invoice with recorded payments.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Download PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        // For now, return a simple view that can be printed
        // In production, you'd use a PDF library like DomPDF or Snappy
        $invoice->load(['brand', 'campaign', 'milestone']);
        $tenant = tenant();

        return view('tenant.invoices.pdf', compact('invoice', 'tenant'));
    }

    /**
     * Send invoice via email
     */
    public function send(Request $request, Invoice $invoice)
    {
        // TODO: Implement email sending
        $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Invoice sent successfully.');
    }

    /**
     * Send invoice via WhatsApp
     */
    public function sendWhatsApp(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $phone = $invoice->brand->phone ?? '';
        $message = "Hi, Please find your invoice #{$invoice->invoice_number} for {$invoice->currency} " . number_format($invoice->total_amount, 2) . ". Due date: {$invoice->due_date->format('M d, Y')}. View: " . route('invoices.public', $invoice->invoice_number);

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        return redirect($whatsappUrl);
    }

    /**
     * Mark invoice as paid
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            // Create payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'campaign_id' => $invoice->campaign_id,
                'brand_id' => $invoice->brand_id,
                'amount' => $invoice->balance_amount,
                'currency' => $invoice->currency,
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'status' => 'completed',
            ]);

            // Update invoice
            $invoice->update([
                'paid_amount' => $invoice->total_amount,
                'balance_amount' => 0,
                'status' => 'paid',
                'paid_at' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
            ]);

            // Update campaign if linked
            if ($invoice->campaign) {
                $campaign = $invoice->campaign;
                $totalPaid = $campaign->invoices()->where('status', 'paid')->sum('total_amount');
                $campaign->update([
                    'paid_amount' => $totalPaid,
                    'payment_status' => $totalPaid >= $campaign->total_amount ? 'completed' : 'partial',
                ]);
            }

            // Update milestone if linked
            if ($invoice->milestone) {
                $invoice->milestone->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Invoice marked as paid.');
    }

    /**
     * Record partial payment
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            // Create payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'campaign_id' => $invoice->campaign_id,
                'brand_id' => $invoice->brand_id,
                'amount' => $validated['amount'],
                'currency' => $invoice->currency,
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'notes' => $validated['notes'],
                'status' => 'completed',
            ]);

            // Update invoice
            $newPaidAmount = $invoice->paid_amount + $validated['amount'];
            $newBalance = $invoice->total_amount - $newPaidAmount;

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'balance_amount' => $newBalance,
                'status' => $newBalance <= 0 ? 'paid' : 'partial',
                'paid_at' => $newBalance <= 0 ? $validated['payment_date'] : null,
            ]);

            // Update campaign if linked
            if ($invoice->campaign) {
                $campaign = $invoice->campaign;
                $totalPaid = $campaign->invoices()->sum('paid_amount');
                $campaign->update([
                    'paid_amount' => $totalPaid,
                    'payment_status' => $totalPaid >= $campaign->total_amount ? 'completed' : 'partial',
                ]);
            }
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Public invoice view (for clients)
     */
    public function publicView(Invoice $invoice)
    {
        // Mark as viewed
        if (!$invoice->viewed_at && in_array($invoice->status, ['sent'])) {
            $invoice->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }

        $tenant = tenant();

        return view('tenant.invoices.public', compact('invoice', 'tenant'));
    }
}