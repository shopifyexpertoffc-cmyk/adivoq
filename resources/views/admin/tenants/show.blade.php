@extends('layouts.admin')

@section('title', 'View Tenant')
@section('page-title', $tenant->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-2xl font-bold">
            {{ strtoupper(substr($tenant->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-2xl font-bold">{{ $tenant->name }}</h2>
            <p class="text-gray-400">{{ $tenant->email }}</p>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-secondary">
            <i data-lucide="edit"></i> Edit
        </a>
        <form action="{{ route('admin.tenants.impersonate', $tenant) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i data-lucide="log-in"></i> Login as Tenant
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="admin-card">
            <h3 class="card-title">Business Information</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Company Name</p>
                    <p class="font-medium">{{ $tenant->company_name ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Phone</p>
                    <p class="font-medium">{{ $tenant->phone ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">GST Number</p>
                    <p class="font-medium">{{ $tenant->gst_number ?? 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Country</p>
                    <p class="font-medium">{{ $tenant->country }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Currency</p>
                    <p class="font-medium">{{ $tenant->currency }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Address</p>
                    <p class="font-medium">{{ $tenant->address ?? 'Not set' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Subscription Info -->
        <div class="admin-card mt-6">
            <h3 class="card-title">Subscription</h3>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Current Plan</p>
                    <span class="badge badge-primary text-lg px-4 py-2">{{ ucfirst($tenant->plan) }}</span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Status</p>
                    <span class="badge badge-{{ $tenant->status == 'active' ? 'success' : 'warning' }} text-lg px-4 py-2">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Trial Ends</p>
                    <p class="font-medium">
                        @if($tenant->trial_ends_at)
                            {{ $tenant->trial_ends_at->format('M d, Y') }}
                            @if($tenant->onTrial())
                                <span class="text-green-400">({{ $tenant->trial_ends_at->diffForHumans() }})</span>
                            @else
                                <span class="text-red-400">(Expired)</span>
                            @endif
                        @else
                            No trial
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div>
        <div class="admin-card">
            <h3 class="card-title">Quick Actions</h3>
            <div class="space-y-3">
                @if($tenant->status == 'active')
                    <form action="{{ route('admin.tenants.suspend', $tenant) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block" onclick="return confirm('Suspend this tenant?')">
                            <i data-lucide="pause"></i> Suspend Tenant
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.tenants.activate', $tenant) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            <i data-lucide="play"></i> Activate Tenant
                        </button>
                    </form>
                @endif
                
                <a href="#" class="btn btn-outline btn-block">
                    <i data-lucide="mail"></i> Send Email
                </a>
            </div>
        </div>
        
        <div class="admin-card mt-6">
            <h3 class="card-title">Domain</h3>
            @if($tenant->domains->first())
                <a href="http://{{ $tenant->domains->first()->domain }}" target="_blank" class="flex items-center gap-2 text-primary hover:underline">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    {{ $tenant->domains->first()->domain }}
                </a>
            @else
                <p class="text-gray-400">No domain configured</p>
            @endif
        </div>
        
        <div class="admin-card mt-6">
            <h3 class="card-title">Timestamps</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Created</span>
                    <span class="info-value">{{ $tenant->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Updated</span>
                    <span class="info-value">{{ $tenant->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection