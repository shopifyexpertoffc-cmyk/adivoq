@extends('layouts.admin')

@section('title', 'Create Admin')
@section('page-title', 'Create Admin User')

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Admin Information</h2>
        
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Full Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Role</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>
            
            <!-- Permissions -->
            <div class="form-group mt-6">
                <label class="form-label">Permissions</label>
                <p class="text-gray-400 text-sm mb-4">Select permissions for this admin (Super Admin has all permissions by default)</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($permissions as $group => $perms)
                        <div class="bg-white/5 rounded-xl p-4">
                            <h4 class="font-semibold mb-3 capitalize">{{ $group }}</h4>
                            <div class="space-y-2">
                                @foreach($perms as $key => $label)
                                    <label class="form-checkbox">
                                        <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                            {{ in_array($key, old('permissions', [])) ? 'checked' : '' }}>
                                        <span class="text-sm">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="plus"></i> Create Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection