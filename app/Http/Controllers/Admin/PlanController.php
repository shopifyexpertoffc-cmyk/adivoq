<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store new plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'max_invoices_per_month' => 'required|integer|min:-1',
            'max_brands' => 'required|integer|min:-1',
            'max_campaigns' => 'required|integer|min:-1',
            'max_team_members' => 'required|integer|min:1',
            'trial_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['features'] = $validated['features'] ?? [];

        $plan = Plan::create($validated);

        ActivityLog::log('created', 'Plan created: ' . $plan->name, $plan, auth('admin')->user());

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update plan
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'max_invoices_per_month' => 'required|integer|min:-1',
            'max_brands' => 'required|integer|min:-1',
            'max_campaigns' => 'required|integer|min:-1',
            'max_team_members' => 'required|integer|min:1',
            'trial_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['features'] = $validated['features'] ?? [];

        $plan->update($validated);

        ActivityLog::log('updated', 'Plan updated: ' . $plan->name, $plan, auth('admin')->user());

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Delete plan
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has active subscriptions
        if ($plan->subscriptions()->active()->count() > 0) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        ActivityLog::log('deleted', 'Plan deleted: ' . $plan->name, null, auth('admin')->user());

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }

    /**
     * Toggle status
     */
    public function toggleStatus(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        return back()->with('success', 'Plan status updated.');
    }
}