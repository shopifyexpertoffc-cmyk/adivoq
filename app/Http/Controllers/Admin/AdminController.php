<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        $admins = Admin::latest()->paginate(20);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $roles = ['super_admin', 'admin', 'support'];
        $permissions = $this->getAvailablePermissions();
        
        return view('admin.admins.create', compact('roles', 'permissions'));
    }

    /**
     * Store new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,support',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['permissions'] = $validated['permissions'] ?? [];

        $admin = Admin::create($validated);

        ActivityLog::log('created', 'Admin user created: ' . $admin->email, $admin, auth('admin')->user());

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Admin $admin)
    {
        $roles = ['super_admin', 'admin', 'support'];
        $permissions = $this->getAvailablePermissions();
        
        return view('admin.admins.edit', compact('admin', 'roles', 'permissions'));
    }

    /**
     * Update admin
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,support',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['permissions'] = $validated['permissions'] ?? [];

        $admin->update($validated);

        ActivityLog::log('updated', 'Admin user updated: ' . $admin->email, $admin, auth('admin')->user());

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Delete admin
     */
    public function destroy(Admin $admin)
    {
        // Cannot delete yourself
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        ActivityLog::log('deleted', 'Admin user deleted: ' . $admin->email, null, auth('admin')->user());

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }

    /**
     * Toggle status
     */
    public function toggleStatus(Admin $admin)
    {
        // Cannot deactivate yourself
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $admin->update(['is_active' => !$admin->is_active]);

        return back()->with('success', 'Admin status updated.');
    }

    /**
     * Get available permissions
     */
    protected function getAvailablePermissions(): array
    {
        return [
            'tenants' => [
                'tenants.view' => 'View Tenants',
                'tenants.create' => 'Create Tenants',
                'tenants.edit' => 'Edit Tenants',
                'tenants.delete' => 'Delete Tenants',
                'tenants.suspend' => 'Suspend/Activate Tenants',
                'tenants.impersonate' => 'Impersonate Tenants',
            ],
            'waitlist' => [
                'waitlist.view' => 'View Waitlist',
                'waitlist.invite' => 'Invite Users',
                'waitlist.delete' => 'Delete Entries',
                'waitlist.export' => 'Export Waitlist',
            ],
            'contacts' => [
                'contacts.view' => 'View Enquiries',
                'contacts.reply' => 'Reply to Enquiries',
                'contacts.delete' => 'Delete Enquiries',
            ],
            'plans' => [
                'plans.view' => 'View Plans',
                'plans.create' => 'Create Plans',
                'plans.edit' => 'Edit Plans',
                'plans.delete' => 'Delete Plans',
            ],
            'settings' => [
                'settings.view' => 'View Settings',
                'settings.edit' => 'Edit Settings',
            ],
            'activity_logs' => [
                'activity_logs.view' => 'View Activity Logs',
            ],
        ];
    }
}