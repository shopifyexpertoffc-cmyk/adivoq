<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Show settings
     */
    public function index()
    {
        $groups = [
            'general' => 'General Settings',
            'mail' => 'Email Settings',
            'payment' => 'Payment Settings',
            'sms' => 'SMS Settings',
            'invoice' => 'Invoice Settings',
        ];

        $settings = [];
        foreach (array_keys($groups) as $group) {
            $settings[$group] = SystemSetting::getGroup($group);
        }

        return view('admin.settings.index', compact('groups', 'settings'));
    }

    /**
     * Show settings by group
     */
    public function group(string $group)
    {
        $settings = SystemSetting::getGroup($group);
        return view('admin.settings.group', compact('group', 'settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            SystemSetting::set($key, $value, $this->guessType($value), $group);
        }

        // Handle file uploads
        foreach ($request->allFiles() as $key => $file) {
            $path = $file->store('settings', 'public');
            SystemSetting::set($key, $path, 'file', $group);
        }

        // Clear settings cache
        Cache::flush();

        ActivityLog::log('updated', 'System settings updated: ' . $group, null, auth('admin')->user());

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Guess setting type from value
     */
    protected function guessType($value): string
    {
        if (is_array($value)) {
            return 'json';
        }

        if (is_bool($value) || in_array(strtolower($value), ['true', 'false', '1', '0', 'yes', 'no'])) {
            return 'boolean';
        }

        if (is_numeric($value) && !str_contains($value, '.')) {
            return 'integer';
        }

        return 'string';
    }
}