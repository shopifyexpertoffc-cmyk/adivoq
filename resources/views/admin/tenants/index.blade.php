@extends('layouts.admin')

@section('title', 'Tenants')
@section('page-title', 'Tenants Management')

@section('content')
<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('admin.tenants.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search tenants..." value="{{ request('search') }}">
        </div>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        
        <select name="plan" class="filter-select" onchange="this.form.submit()">
            <option value="">All Plans</option>
            <option value="free" {{ request('plan') == 'free' ? 'selected' : '' }}>Free</option>
            <option value="pro" {{ request('plan') == 'pro' ? 'selected' : '' }}>Pro</option>
            <option value="agency" {{ request('plan') == 'agency' ? 'selected' : '' }}>Agency</option>
        </select>
        
        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i> Add Tenant
        </a>
    </form>
</div>

<!-- Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Domain</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                    <tr>
                        <td>
                            <div class="table-user">
                                <div class="table-user-avatar">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div class="table-user-info">
                                    <span class="table-user-name">{{ $tenant->name }}</span>
                                    <span class="table-user-email">{{ $tenant->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($tenant->domains->first())
                                <a href="http://{{ $tenant->domains->first()->domain }}" target="_blank" class="text-primary">
                                    {{ $tenant->domains->first()->domain }}
                                </a>
                            @else
                                <span class="text-gray-400">No domain</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $tenant->plan == 'pro' ? 'primary' : ($tenant->plan == 'agency' ? 'info' : 'secondary') }}">
                                {{ ucfirst($tenant->plan) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $tenant->status == 'active' ? 'success' : ($tenant->status == 'suspended' ? 'danger' : 'warning') }}">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </td>
                        <td class="text-gray-400">
                            {{ $tenant->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.tenants.show', $tenant) }}" class="table-action-btn" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="table-action-btn" title="Edit">
                                    <i data-lucide="edit"></i>
                                </a>
                                @if($tenant->status == 'active')
                                    <form action="{{ route('admin.tenants.suspend', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="table-action-btn danger" title="Suspend" onclick="return confirm('Are you sure?')">
                                            <i data-lucide="pause"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.tenants.activate', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="table-action-btn" title="Activate">
                                            <i data-lucide="play"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="users"></i>
                                </div>
                                <h3 class="empty-state-title">No tenants found</h3>
                                <p class="empty-state-text">Get started by creating a new tenant.</p>
                                <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                                    <i data-lucide="plus"></i> Add First Tenant
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tenants->hasPages())
        <div class="mt-6">
            {{ $tenants->links() }}
        </div>
    @endif
</div>
@endsection