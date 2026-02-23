@extends('layouts.tenant')

@section('title', 'Brands')
@section('page-title', 'Brands')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="building-2"></i>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Brands</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-card-value">{{ $stats['active'] }}</div>
        <div class="stat-card-label">Active</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="indian-rupee"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_revenue']) }}</div>
        <div class="stat-card-label">Total Revenue</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('brands.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search brands..." value="{{ request('search') }}">
        </div>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        
        <a href="{{ route('brands.create') }}" class="btn btn-primary ml-auto">
            <i data-lucide="plus"></i> Add Brand
        </a>
    </form>
</div>

<!-- Brands Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($brands as $brand)
        <div class="admin-card hover:border-primary/30 transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-12 rounded-xl object-cover">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center text-lg font-bold">
                            {{ strtoupper(substr($brand->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold">{{ $brand->name }}</h3>
                        <p class="text-sm text-gray-400">{{ $brand->industry ?? 'No industry' }}</p>
                    </div>
                </div>
                <span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-secondary' }}">
                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-400">Campaigns</p>
                    <p class="font-semibold">{{ $brand->campaigns_count }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Invoices</p>
                    <p class="font-semibold">{{ $brand->invoices_count }}</p>
                </div>
            </div>
            
            @if($brand->contact_person || $brand->email)
                <div class="border-t border-white/10 pt-4 mb-4">
                    @if($brand->contact_person)
                        <p class="text-sm text-gray-400">{{ $brand->contact_person }}</p>
                    @endif
                    @if($brand->email)
                        <p class="text-sm text-gray-400">{{ $brand->email }}</p>
                    @endif
                </div>
            @endif
            
            <div class="flex gap-2">
                <a href="{{ route('brands.show', $brand) }}" class="btn btn-outline btn-sm flex-1">
                    <i data-lucide="eye"></i> View
                </a>
                <a href="{{ route('brands.edit', $brand) }}" class="btn btn-secondary btn-sm flex-1">
                    <i data-lucide="edit"></i> Edit
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i data-lucide="building-2"></i>
                </div>
                <h3 class="empty-state-title">No brands yet</h3>
                <p class="empty-state-text">Add your first brand to get started.</p>
                <a href="{{ route('brands.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Add First Brand
                </a>
            </div>
        </div>
    @endforelse
</div>

@if($brands->hasPages())
    <div class="mt-6">
        {{ $brands->links() }}
    </div>
@endif
@endsection