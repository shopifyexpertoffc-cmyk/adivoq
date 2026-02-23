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

        $workspace = strtolower(trim($data['workspace']));

        $tenant = Tenant::query()
            ->with('domains')
            ->where('id', $workspace)
            ->orWhereHas('domains', function ($query) use ($workspace) {
                $query->where('domain', $workspace)
                    ->orWhere('domain', 'like', $workspace . '.%');
            })
            ->first();

        if (! $tenant) {
            return back()->withInput()->withErrors(['workspace' => 'Workspace not found.']);
        }

        $domain = $tenant->domains()->value('domain');

        if (! $domain) {
            return back()->withInput()->withErrors(['workspace' => 'Workspace domain is not configured.']);
        }

        $scheme = $request->isSecure()
            ? 'https'
            : (parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https');

        return redirect()->away("{$scheme}://{$domain}/login");
    }
}
