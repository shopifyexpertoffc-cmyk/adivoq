@extends('layouts.tenant')

@section('title', 'Campaigns')
@section('page-title', 'Campaigns')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="briefcase"></i>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Campaigns</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="play-circle"></i>
        </div>
        <div class="stat-card-value">{{ $stats['active'] }}</div>
        <div class="stat-card-label">Active</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="indian-rupee"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_value']) }}</div>
        <div class="stat-card-label">Total Value</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <i data-lucide="clock"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['pending_payments']) }}</div>
        <div class="stat-card-label">Pending Payments</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('campaigns.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search campaigns..." value="{{ request('search') }}">
        </div>
        
        <select name="brand_id" class="filter-select" onchange="this.form.submit()">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="negotiation" {{ request('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        
        <select name="payment_status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Payment Status</option>
            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        
        <a href="{{ route('campaigns.create') }}" class="btn btn-primary ml-auto">
            <i data-lucide="plus"></i> New Campaign
        </a>
    </form>
</div>

<!-- Campaigns Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Brand</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium hover:text-primary">
                                {{ $campaign->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                @if($campaign->platform)
                                    <span class="text-xs bg-white/5 px-2 py-1 rounded">{{ $campaign->platform }}</span>
                                @endif
                                @if($campaign->deliverable_date)
                                    <span class="text-xs text-gray-400">
                                        <i data-lucide="calendar" class="w-3 h-3 inline"></i>
                                        {{ $campaign->deliverable_date->format('M d') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('brands.show', $campaign->brand) }}" class="hover:text-primary">
                                {{ $campaign->brand->name }}
                            </a>
                        </td>
                        <td>
                            <p class="font-semibold">₹{{ number_format($campaign->total_amount) }}</p>
                            <p class="text-xs text-gray-400">Paid: ₹{{ number_format($campaign->paid_amount) }}</p>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'negotiation' => 'warning',
                                    'confirmed' => 'info',
                                    'in_progress' => 'primary',
                                    'delivered' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$campaign->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $campaign->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $campaign->payment_status === 'completed' ? 'success' : ($campaign->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($campaign->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <div class="w-20">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span>{{ $campaign->payment_progress }}%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $campaign->payment_progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="table-action-btn" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="table-action-btn" title="Edit">
                                    <i data-lucide="edit"></i>
                                </a>
                                <a href="{{ route('invoices.create', ['campaign_id' => $campaign->id, 'brand_id' => $campaign->brand_id]) }}" class="table-action-btn" title="Create Invoice">
                                    <i data-lucide="file-plus"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="briefcase"></i>
                                </div>
                                <h3 class="empty-state-title">No campaigns yet</h3>
                                <p class="empty-state-text">Create your first campaign to track brand deals.</p>
                                <a href="{{ route('campaigns.create') }}" class="btn btn-primary">
                                    <i data-lucide="plus"></i> Create Campaign
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($campaigns->hasPages())
        <div class="mt-6">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection