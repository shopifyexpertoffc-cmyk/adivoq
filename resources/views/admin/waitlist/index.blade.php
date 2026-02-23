@extends('layouts.admin')

@section('title', 'Waitlist')
@section('page-title', 'Waitlist Management')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="users"></i>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Signups</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <i data-lucide="clock"></i>
        </div>
        <div class="stat-card-value">{{ $stats['pending'] }}</div>
        <div class="stat-card-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="send"></i>
        </div>
        <div class="stat-card-value">{{ $stats['invited'] }}</div>
        <div class="stat-card-label">Invited</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="user-check"></i>
        </div>
        <div class="stat-card-value">{{ $stats['registered'] }}</div>
        <div class="stat-card-label">Registered</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('admin.waitlist.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
        </div>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="invited" {{ request('status') == 'invited' ? 'selected' : '' }}>Invited</option>
            <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Registered</option>
        </select>
        
        <select name="creator_type" class="filter-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="influencer" {{ request('creator_type') == 'influencer' ? 'selected' : '' }}>Influencer</option>
            <option value="freelancer" {{ request('creator_type') == 'freelancer' ? 'selected' : '' }}>Freelancer</option>
            <option value="agency" {{ request('creator_type') == 'agency' ? 'selected' : '' }}>Agency</option>
            <option value="other" {{ request('creator_type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        
        <form action="{{ route('admin.waitlist.export') }}" method="POST" class="ml-auto">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <i data-lucide="download"></i> Export CSV
            </button>
        </form>
    </form>
</div>

<!-- Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Followers</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waitlist as $entry)
                    <tr>
                        <td class="font-mono text-gray-400">{{ $entry->position }}</td>
                        <td>
                            <div class="table-user">
                                <div class="table-user-avatar">
                                    {{ strtoupper(substr($entry->name, 0, 1)) }}
                                </div>
                                <div class="table-user-info">
                                    <span class="table-user-name">{{ $entry->name }}</span>
                                    <span class="table-user-email">{{ $entry->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $entry->phone }}</td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ ucfirst($entry->creator_type ?? 'N/A') }}
                            </span>
                        </td>
                        <td>{{ $entry->followers ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $entry->status == 'invited' ? 'info' : ($entry->status == 'registered' ? 'success' : 'warning') }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                        <td class="text-gray-400">
                            {{ $entry->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="table-actions">
                                @if($entry->status == 'pending')
                                    <form action="{{ route('admin.waitlist.invite', $entry) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="table-action-btn" title="Send Invite">
                                            <i data-lucide="send"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.waitlist.destroy', $entry) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-action-btn danger" title="Delete" onclick="return confirm('Delete this entry?')">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="clock"></i>
                                </div>
                                <h3 class="empty-state-title">No waitlist entries</h3>
                                <p class="empty-state-text">Waitlist signups will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($waitlist->hasPages())
        <div class="mt-6">
            {{ $waitlist->links() }}
        </div>
    @endif
</div>
@endsection