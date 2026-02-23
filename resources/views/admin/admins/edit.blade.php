@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin: ' . $admin->name)

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Admin Information</h2>
        
        <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Full Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input">
                    <span class="form-hint">Leave blank to keep current password</span>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $admin->phone) }}">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Role</label>
                    <select name="role" class="form-select" required {{ $admin->id === auth('admin')->id() ? 'disabled' : '' }}>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $admin->role) == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                    @if($admin->id === auth('admin')->id())
                        <input type="hidden" name="role" value="{{ $admin->role }}">
                        <span class="form-hint">You cannot change your own role</span>
                    @endif
                    @error('role')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" 
                        {{ old('is_active', $admin->is_active) ? 'checked' : '' }}
                        {{ $admin->id === auth('admin')->id() ? 'disabled' : '' }}>
                    <span>Active</span>
                </label>
                @if($admin->id === auth('admin')->id())
                    <input type="hidden" name="is_active" value="1">
                @endif
            </div>
            
            <!-- Permissions -->
            @if($admin->role !== 'super_admin')
            <div class="form-group mt-6">
                <label class="form-label">Permissions</label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($permissions as $group => $perms)
                        <div class="bg-white/5 rounded-xl p-4">
                            <h4 class="font-semibold mb-3 capitalize">{{ $group }}</h4>
                            <div class="space-y-2">
                                @foreach($perms as $key => $label)
                                    <label class="form-checkbox">
                                        <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                            {{ in_array($key, old('permissions', $admin->permissions ?? [])) ? 'checked' : '' }}>
                                        <span class="text-sm">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
                <div class="alert alert-info mt-6">
                    <i data-lucide="info"></i>
                    Super Admin has all permissions by default.
                </div>
            @endif
            
            <div class="form-actions">
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i> Update Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection