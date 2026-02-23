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

    $tenant = Tenant::find($tenantId);
    $domain = $tenant?->domains()->value('domain');

    if (! $domain) {
        return back()->withInput()->withErrors(['workspace' => 'Workspace domain is not configured.']);
    }

    return redirect()->away('https://' . $domain . '/login');
}
}