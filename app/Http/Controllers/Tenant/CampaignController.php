<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Milestone;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = Campaign::with(['brand', 'milestones']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
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

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $campaigns = $query->latest()->paginate(10);
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        // Stats
        $stats = [
            'total' => Campaign::count(),
            'active' => Campaign::whereIn('status', ['confirmed', 'in_progress'])->count(),
            'total_value' => Campaign::sum('total_amount'),
            'pending_payments' => Campaign::where('payment_status', '!=', 'completed')->sum(\DB::raw('total_amount - paid_amount')),
        ];

        return view('tenant.campaigns.index', compact('campaigns', 'brands', 'stats'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $selectedBrand = $request->filled('brand_id') ? Brand::find($request->brand_id) : null;

        return view('tenant.campaigns.create', compact('brands', 'selectedBrand'));
    }

    /**
     * Store new campaign
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'platform' => 'nullable|string|max:50',
            'campaign_type' => 'nullable|string|max:50',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'advance_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'deliverable_date' => 'nullable|date',
            'status' => 'required|in:draft,negotiation,confirmed,in_progress,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:2000',
            'agency_commission_percent' => 'nullable|numeric|min:0|max:100',
            'manager_commission_percent' => 'nullable|numeric|min:0|max:100',
            // Milestones
            'milestones' => 'nullable|array',
            'milestones.*.title' => 'required_with:milestones|string|max:255',
            'milestones.*.amount' => 'required_with:milestones|numeric|min:0',
            'milestones.*.due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['paid_amount'] = 0;
        $validated['payment_status'] = 'pending';

        // Extract milestones before creating campaign
        $milestones = $validated['milestones'] ?? [];
        unset($validated['milestones']);

        $campaign = Campaign::create($validated);

        // Create milestones
        foreach ($milestones as $index => $milestone) {
            $campaign->milestones()->create([
                'title' => $milestone['title'],
                'description' => $milestone['description'] ?? null,
                'amount' => $milestone['amount'],
                'due_date' => $milestone['due_date'] ?? null,
                'status' => 'pending',
                'order' => $index,
            ]);
        }

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Show campaign details
     */
    public function show(Campaign $campaign)
    {
        $campaign->load(['brand', 'milestones', 'invoices.payments']);

        return view('tenant.campaigns.show', compact('campaign'));
    }

    /**
     * Show edit form
     */
    public function edit(Campaign $campaign)
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $campaign->load('milestones');

        return view('tenant.campaigns.edit', compact('campaign', 'brands'));
    }

    /**
     * Update campaign
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'platform' => 'nullable|string|max:50',
            'campaign_type' => 'nullable|string|max:50',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'advance_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'deliverable_date' => 'nullable|date',
            'status' => 'required|in:draft,negotiation,confirmed,in_progress,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:2000',
            'agency_commission_percent' => 'nullable|numeric|min:0|max:100',
            'manager_commission_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Update payment status based on amounts
        if ($campaign->paid_amount >= $validated['total_amount']) {
            $validated['payment_status'] = 'completed';
        } elseif ($campaign->paid_amount > 0) {
            $validated['payment_status'] = 'partial';
        } else {
            $validated['payment_status'] = 'pending';
        }

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Delete campaign
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->invoices()->exists()) {
            return back()->with('error', 'Cannot delete campaign with existing invoices.');
        }

        $campaign->milestones()->delete();
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Add milestone
     */
    public function addMilestone(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $validated['order'] = $campaign->milestones()->count();
        $validated['status'] = 'pending';

        $campaign->milestones()->create($validated);

        return back()->with('success', 'Milestone added successfully.');
    }

    /**
     * Update milestone
     */
    public function updateMilestone(Request $request, Campaign $campaign, Milestone $milestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,submitted,approved,paid,cancelled',
        ]);

        if ($validated['status'] === 'paid' && !$milestone->paid_at) {
            $validated['paid_at'] = now();
        }

        $milestone->update($validated);

        return back()->with('success', 'Milestone updated successfully.');
    }

    /**
     * Delete milestone
     */
    public function deleteMilestone(Campaign $campaign, Milestone $milestone)
    {
        if ($milestone->invoice) {
            return back()->with('error', 'Cannot delete milestone with existing invoice.');
        }

        $milestone->delete();

        return back()->with('success', 'Milestone deleted successfully.');
    }
}