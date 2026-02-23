@extends('layouts.admin')

@section('title', 'Activity Log Details')
@section('page-title', 'Activity Log Details')

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <div class="flex items-center justify-between mb-6">
            <span class="badge badge-{{ 
                in_array($activityLog->action, ['created', 'login', 'activated']) ? 'success' : 
                (in_array($activityLog->action, ['deleted', 'suspended']) ? 'danger' : 'secondary') 
            }} text-lg px-4 py-2">
                {{ ucfirst($activityLog->action) }}
            </span>
            <span class="text-gray-400">{{ $activityLog->created_at->format('M d, Y H:i:s') }}</span>
        </div>
        
        @if($activityLog->description)
            <p class="text-lg mb-6">{{ $activityLog->description }}</p>
        @endif
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">Performed By</p>
                @if($activityLog->causer)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center font-bold">
                            {{ strtoupper(substr($activityLog->causer->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $activityLog->causer->name }}</p>
                            <p class="text-sm text-gray-400">{{ class_basename($activityLog->causer_type) }}</p>
                        </div>
                    </div>
                @else
                    <p class="font-medium">System</p>
                @endif
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Subject</p>
                @if($activityLog->subject)
                    <p class="font-medium">{{ class_basename($activityLog->subject_type) }} #{{ $activityLog->subject_id }}</p>
                @else
                    <p class="text-gray-400">-</p>
                @endif
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">IP Address</p>
                <p class="font-mono">{{ $activityLog->ip_address ?? '-' }}</p>
            </div>
            
            <div>
                <p class="text-gray-400 text-sm mb-1">Tenant</p>
                <p class="font-medium">{{ $activityLog->tenant_id ?? 'Central' }}</p>
            </div>
        </div>
        
        @if($activityLog->properties && count($activityLog->properties) > 0)
            <div class="border-t border-white/10 pt-6">
                <h3 class="font-semibold mb-4">Properties</h3>
                <div class="bg-black/30 rounded-xl p-4 overflow-x-auto">
                    <pre class="text-sm text-gray-300">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif
        
        @if($activityLog->user_agent)
            <div class="border-t border-white/10 pt-6 mt-6">
                <h3 class="font-semibold mb-4">User Agent</h3>
                <p class="text-sm text-gray-400 break-all">{{ $activityLog->user_agent }}</p>
            </div>
        @endif
        
        <div class="form-actions">
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i> Back to Logs
            </a>
        </div>
    </div>
</div>
@endsection