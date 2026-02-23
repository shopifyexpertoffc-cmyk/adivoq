<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function showForm()
    {
        return view('web.auth.workspace');
    }

public function redirect(Request $request)
{
    $data = $request->validate([
        'workspace' => 'required|string',
    ]);

    $tenantId = strtolower(trim($data['workspace']));

    if (! \App\Models\Tenant::find($tenantId)) {
        return back()->withInput()->withErrors(['workspace' => 'Workspace not found.']);
    }

    // Redirect using route name tenant.login
    return redirect()->route('tenant.login', ['tenant' => $tenantId]);
}
}