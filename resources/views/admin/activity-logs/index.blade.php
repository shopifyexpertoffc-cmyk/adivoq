@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <select name="action" class="filter-select" onchange="this.form.submit()">
            <option value="">All Actions</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                    {{ ucfirst($action) }}
                </option>
            @endforeach
        </select>
        
        <input type="date" name="from_date" class="form-input" style="width: auto;" value="{{ request('from_date') }}" onchange="this.form.submit()">
        <input type="date" name="to_date" class="form-input" style="width: auto;" value="{{ request('to_date') }}" onchange="this.form.submit()">
        
        @if(request()->hasAny(['action', 'from_date', 'to_date']))
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                <i data-lucide="x"></i> Clear
            </a>
        @endif
    </form>
</div>

<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <span class="badge badge-{{ 
                                in_array($log->action, ['created', 'login', 'activated']) ? 'success' : 
                                (in_array($log->action, ['deleted', 'suspended']) ? 'danger' : 'secondary') 
                            }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-gray-300">{{ $log->description ?? '-' }}</span>
                        </td>
                        <td>
                            @if($log->causer)
                                <div class="table-user">
                                    <div class="table-user-avatar text-xs">
                                        {{ strtoupper(substr($log->causer->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="table-user-info">
                                        <span class="table-user-name">{{ $log->causer->name ?? 'System' }}</span>
                                        <span class="table-user-email text-xs">{{ class_basename($log->causer_type) }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">System</span>
                            @endif
                        </td>
                        <td class="font-mono text-sm text-gray-400">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="text-gray-400">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.activity-logs.show', $log) }}" class="table-action-btn" title="View Details">
                                <i data-lucide="eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <h3 class="empty-state-title">No activity logs</h3>
                                <p class="empty-state-text">Activity will be logged here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection