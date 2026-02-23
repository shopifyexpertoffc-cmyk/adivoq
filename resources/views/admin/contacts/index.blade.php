@extends('layouts.admin')

@section('title', 'Contact Enquiries')
@section('page-title', 'Contact Enquiries')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="mail"></i>
        </div>
        <div class="stat-card-value">{{ $stats['total'] }}</div>
        <div class="stat-card-label">Total Enquiries</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <i data-lucide="bell"></i>
        </div>
        <div class="stat-card-value">{{ $stats['new'] }}</div>
        <div class="stat-card-label">New</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="eye"></i>
        </div>
        <div class="stat-card-value">{{ $stats['read'] }}</div>
        <div class="stat-card-label">Read</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-card-value">{{ $stats['replied'] }}</div>
        <div class="stat-card-label">Replied</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('admin.contacts.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search enquiries..." value="{{ request('search') }}">
        </div>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </form>
</div>

<!-- Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>
                            <div class="table-user">
                                <div class="table-user-avatar">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="table-user-info">
                                    <span class="table-user-name">{{ $contact->name }}</span>
                                    <span class="table-user-email">{{ $contact->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="hover:text-primary">
                                {{ Str::limit($contact->subject, 50) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-{{ $contact->status == 'new' ? 'danger' : ($contact->status == 'replied' ? 'success' : 'secondary') }}">
                                {{ ucfirst($contact->status) }}
                            </span>
                        </td>
                        <td class="text-gray-400">
                            {{ $contact->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="table-action-btn" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-action-btn danger" title="Delete" onclick="return confirm('Delete this enquiry?')">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="mail"></i>
                                </div>
                                <h3 class="empty-state-title">No enquiries yet</h3>
                                <p class="empty-state-text">Contact form submissions will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($contacts->hasPages())
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection