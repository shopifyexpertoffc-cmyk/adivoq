<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display listing of tenants
     */
    public function index(Request $request)
    {
        $query = Tenant::with('domains');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by plan
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        $tenants = $query->latest()->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }
    
    protected function getBaseDomain(): string
{
    // Priority: TENANT_BASE_DOMAIN > CENTRAL_DOMAIN > app.url host
    return env(
        'TENANT_BASE_DOMAIN',
        env('CENTRAL_DOMAIN', parse_url(config('app.url'), PHP_URL_HOST))
    );
}

    /**
     * Show create form
     */
public function create()
{
    $plans = Plan::active()->get();
    $baseDomain = $this->getBaseDomain();   // e.g. adavoq.com

    return view('admin.tenants.create', compact('plans', 'baseDomain'));
}

    /**
     * Store new tenant
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:tenants,email',
    //         'phone' => 'nullable|string|max:20',
    //         'company_name' => 'nullable|string|max:255',
    //         'plan' => 'required|string',
    //         'domain' => 'required|string|alpha_dash|unique:domains,domain',
    //         'country' => 'required|string|size:2',
    //         'currency' => 'required|string|size:3',
    //     ]);

    //     // Generate tenant ID
    //     $tenantId = Str::slug($validated['domain']);

    //     $tenant = Tenant::create([
    //         'id' => $tenantId,
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'phone' => $validated['phone'],
    //         'company_name' => $validated['company_name'],
    //         'plan' => $validated['plan'],
    //         'country' => $validated['country'],
    //         'currency' => $validated['currency'],
    //         'status' => 'active',
    //         'trial_ends_at' => now()->addDays(14),
    //     ]);

    //     // Create domain
    //     $tenant->domains()->create([
    //         'domain' => $validated['domain'] . '.' . config('tenancy.central_domains')[0],
    //     ]);

    //     // Log activity
    //     ActivityLog::log('created', 'Tenant created: ' . $tenant->name, $tenant, auth('admin')->user());

    //     return redirect()->route('admin.tenants.index')
    //         ->with('success', 'Tenant created successfully.');
    // }
    
public function store(Request $request)
{
    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|unique:tenants,email',
        'phone'        => 'nullable|string|max:20',
        'company_name' => 'nullable|string|max:255',
        'plan'         => 'required|string',
        'domain'       => 'required|string|alpha_dash', // subdomain only
        'country'      => 'required|string|size:2',
        'currency'     => 'required|string|size:3',
    ]);

    $tenantId = \Illuminate\Support\Str::slug($validated['domain']);

    $tenant = Tenant::create([
        'id'           => $tenantId,
        'name'         => $validated['name'],
        'email'        => $validated['email'],
        'phone'        => $validated['phone'],
        'company_name' => $validated['company_name'],
        'plan'         => $validated['plan'],
        'country'      => $validated['country'],
        'currency'     => $validated['currency'],
        'status'       => 'active',
        'trial_ends_at'=> now()->addDays(14),
    ]);

    $baseDomain = $this->getBaseDomain();   // will resolve to adivoq.com in production
    $fullDomain = $validated['domain'] . '.' . $baseDomain;

    $tenant->domains()->create([
        'domain' => $fullDomain,            // e.g. sani.adivoq.com
    ]);

    // Optional activity log
    ActivityLog::log(
        'created',
        'Tenant created: ' . $tenant->name,
        $tenant,
        auth('admin')->user(),
        ['tenant_id' => $tenant->id, 'plan' => $tenant->plan]
    );

    return redirect()->route('admin.tenants.index')
                     ->with('success', 'Tenant created successfully.');
}

    /**
     * Show tenant details
     */
    public function show(Tenant $tenant)
    {
        $tenant->load('domains');
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show edit form
     */
    public function edit(Tenant $tenant)
    {
        $plans = Plan::active()->get();
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    /**
     * Update tenant
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id . ',id',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'plan' => 'required|string',
            'country' => 'required|string|size:2',
            'currency' => 'required|string|size:3',
            'gst_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        $tenant->update($validated);

        // Log activity
        ActivityLog::log('updated', 'Tenant updated: ' . $tenant->name, $tenant, auth('admin')->user());

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Delete tenant
     */
    public function destroy(Tenant $tenant)
    {
        $name = $tenant->name;
        
        // Log before deleting
        ActivityLog::log('deleted', 'Tenant deleted: ' . $name, null, auth('admin')->user(), [
            'tenant_id' => $tenant->id,
            'tenant_email' => $tenant->email,
        ]);

        $tenant->delete();

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Suspend tenant
     */
    public function suspend(Tenant $tenant)
    {
        $tenant->update(['status' => 'suspended']);

        ActivityLog::log('suspended', 'Tenant suspended: ' . $tenant->name, $tenant, auth('admin')->user());

        return back()->with('success', 'Tenant suspended successfully.');
    }

    /**
     * Activate tenant
     */
    public function activate(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);

        ActivityLog::log('activated', 'Tenant activated: ' . $tenant->name, $tenant, auth('admin')->user());

        return back()->with('success', 'Tenant activated successfully.');
    }

    /**
     * Impersonate tenant (login as tenant)
     */
    public function impersonate(Tenant $tenant)
    {
        // Store admin ID in session for returning later
        session(['impersonating_from_admin' => auth('admin')->id()]);

        ActivityLog::log('impersonated', 'Admin impersonated tenant: ' . $tenant->name, $tenant, auth('admin')->user());

        // Redirect to tenant's dashboard with special token
        $domain = $tenant->domains->first()?->domain;
        
        if (!$domain) {
            return back()->with('error', 'Tenant has no domain configured.');
        }

        // Create impersonation token (you'll need to handle this in tenant auth)
        $token = encrypt([
            'tenant_id' => $tenant->id,
            'admin_id' => auth('admin')->id(),
            'expires' => now()->addMinutes(5)->timestamp,
        ]);

        return redirect("http://{$domain}/impersonate?token={$token}");
    }
}