@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: var(--gradient-green);">
                <i data-lucide="users" style="color: white;"></i>
            </div>
            <div class="stat-card-value">{{ $stats['total_tenants'] ?? 0 }}</div>
            <div class="stat-card-label">Total Tenants</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-icon" style="background: var(--gradient-blue);">
                <i data-lucide="user-check" style="color: white;"></i>
            </div>
            <div class="stat-card-value">{{ $stats['active_tenants'] ?? 0 }}</div>
            <div class="stat-card-label">Active Tenants</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-icon" style="background: var(--gradient-purple);">
                <i data-lucide="clock" style="color: white;"></i>
            </div>
            <div class="stat-card-value">{{ $stats['waitlist_count'] ?? 0 }}</div>
            <div class="stat-card-label">Waitlist Signups</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-icon" style="background: var(--gradient-orange);">
                <i data-lucide="mail" style="color: white;"></i>
            </div>
            <div class="stat-card-value">{{ $stats['new_enquiries'] ?? 0 }}</div>
            <div class="stat-card-label">New Enquiries</div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Recent Tenants -->
        <div class="admin-form">
            <h2 class="text-xl font-bold mb-4">Recent Tenants</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTenants as $tenant)
                        <tr>
                            <td>
                                <div>
                                    <div class="font-semibold">{{ $tenant->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $tenant->email }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge">{{ ucfirst($tenant->plan) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $tenant->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td class="text-gray-400">
                                {{ $tenant->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400">No tenants yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Recent Waitlist -->
        <div class="admin-form">
            <h2 class="text-xl font-bold mb-4">Recent Waitlist Signups</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentWaitlist as $entry)
                        <tr>
                            <td>
                                <div>
                                    <div class="font-semibold">{{ $entry->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $entry->email }}</div>
                                </div>
                            </td>
                            <td>{{ ucfirst($entry->creator_type ?? 'N/A') }}</td>
                            <td>#{{ $entry->position }}</td>
                            <td class="text-gray-400">
                                {{ $entry->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400">No signups yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection