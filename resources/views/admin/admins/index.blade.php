@extends('layouts.admin')

@section('title', 'Admin Users')
@section('page-title', 'Admin Users')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-400">Manage admin users who can access this panel.</p>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
        <i data-lucide="plus"></i> Add Admin
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>
                            <div class="table-user">
                                <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}" class="w-10 h-10 rounded-lg object-cover">
                                <div class="table-user-info">
                                    <span class="table-user-name">{{ $admin->name }}</span>
                                    <span class="table-user-email">{{ $admin->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $admin->role == 'super_admin' ? 'primary' : ($admin->role == 'admin' ? 'info' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $admin->is_active ? 'success' : 'danger' }}">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-gray-400">
                            {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-gray-400">
                            {{ $admin->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="table-action-btn" title="Edit">
                                    <i data-lucide="edit"></i>
                                </a>
                                @if($admin->id !== auth('admin')->id())
                                    <form action="{{ route('admin.admins.toggleStatus', $admin) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="table-action-btn" title="{{ $admin->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i data-lucide="{{ $admin->is_active ? 'user-x' : 'user-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="table-action-btn danger" title="Delete" onclick="return confirm('Delete this admin?')">
                                            <i data-lucide="trash-2"></i>
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
                                    <i data-lucide="shield"></i>
                                </div>
                                <h3 class="empty-state-title">No admin users</h3>
                                <p class="empty-state-text">Add admin users to manage the platform.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($admins->hasPages())
        <div class="mt-6">
            {{ $admins->links() }}
        </div>
    @endif
</div>
@endsection