<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show settings
     */
    public function index()
    {
        $tenant = tenant();
        return view('tenant.settings.index', compact('tenant'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $tenant = tenant();

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|size:2',
            'currency' => 'required|string|size:3',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($tenant->logo) {
                Storage::disk('public')->delete($tenant->logo);
            }
            $validated['logo'] = $request->file('logo')->store('tenant/logos', 'public');
        }

        $tenant->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Billing settings
     */
    public function billing()
    {
        $tenant = tenant();
        $subscription = $tenant->subscription ?? null;
        
        return view('tenant.settings.billing', compact('tenant', 'subscription'));
    }

    /**
     * Invoice settings
     */
    public function invoice()
    {
        $tenant = tenant();
        return view('tenant.settings.invoice', compact('tenant'));
    }
}