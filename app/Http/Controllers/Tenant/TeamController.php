<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        // List all users for this tenant
        $users = User::orderBy('role')->orderBy('name')->get();

        return view('tenant.team.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin', 'manager', 'member']; // owner reserved for main account
        return view('tenant.team.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role'  => ['required', Rule::in(['admin', 'manager', 'member'])],
        ]);

        $password = Str::random(10); // temp password

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'role'      => $validated['role'],
            'password'  => Hash::make($password),
            'is_active' => true,
        ]);

        // Show the generated password once
        return redirect()
            ->route('team.index')
            ->with('success', 'Team member created. Temp password: ' . $password);
    }

    public function edit(User $team)
    {
        $roles = ['admin', 'manager', 'member'];
        return view('tenant.team.edit', ['user' => $team, 'roles' => $roles]);
    }

    public function update(Request $request, User $team)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($team->id)],
            'phone'     => 'nullable|string|max:20',
            'role'      => ['required', Rule::in(['admin', 'manager', 'member'])],
            'is_active' => 'boolean',
        ]);

        $team->update($validated);

        return redirect()
            ->route('team.index')
            ->with('success', 'Team member updated.');
    }

    public function destroy(User $team)
    {
        if ($team->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $team->delete();

        return redirect()
            ->route('team.index')
            ->with('success', 'Team member deleted.');
    }
}