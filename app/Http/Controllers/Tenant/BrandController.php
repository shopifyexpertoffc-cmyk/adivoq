<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = Brand::withCount(['campaigns', 'invoices']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $brands = $query->latest()->paginate(12);

        // Stats
        $stats = [
            'total' => Brand::count(),
            'active' => Brand::where('is_active', true)->count(),
            'total_revenue' => Brand::withSum(['invoices' => function ($q) {
                $q->where('status', 'paid');
            }], 'total_amount')->get()->sum('invoices_sum_total_amount') ?? 0,
        ];

        return view('tenant.brands.index', compact('brands', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('tenant.brands.create');
    }

    /**
     * Store new brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|string|max:500',
            'gst_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = true;

        Brand::create($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Show brand details
     */
    public function show(Brand $brand)
    {
        $brand->load(['campaigns' => function ($q) {
            $q->latest()->take(5);
        }, 'invoices' => function ($q) {
            $q->latest()->take(5);
        }]);

        $stats = [
            'total_campaigns' => $brand->campaigns()->count(),
            'active_campaigns' => $brand->campaigns()->whereIn('status', ['confirmed', 'in_progress'])->count(),
            'total_invoiced' => $brand->invoices()->sum('total_amount'),
            'total_paid' => $brand->invoices()->where('status', 'paid')->sum('total_amount'),
            'pending_amount' => $brand->invoices()->whereIn('status', ['sent', 'viewed', 'partial'])->sum('balance_amount'),
        ];

        return view('tenant.brands.show', compact('brand', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(Brand $brand)
    {
        return view('tenant.brands.edit', compact('brand'));
    }

    /**
     * Update brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|string|max:500',
            'gst_number' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    /**
     * Delete brand
     */
    public function destroy(Brand $brand)
    {
        // Check for associated data
        if ($brand->campaigns()->exists()) {
            return back()->with('error', 'Cannot delete brand with existing campaigns.');
        }

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('brands.index')
            ->with('success', 'Brand deleted successfully.');
    }
}