@extends('layouts.admin')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="lg:col-span-2">
        <div class="admin-card">
            <h2 class="card-title">Profile Information</h2>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $admin->phone) }}">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <div class="avatar-upload">
                        <img src="{{ $admin->avatar_url }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden">
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('avatarInput').click()">
                            <i data-lucide="upload"></i> Change Photo
                        </button>
                    </div>
                    @error('avatar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Change Password -->
    <div>
        <div class="admin-card">
            <h2 class="card-title">Change Password</h2>
            
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input" required>
                    @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i data-lucide="key"></i> Update Password
                </button>
            </form>
        </div>
        
        <!-- Account Info -->
        <div class="admin-card mt-6">
            <h2 class="card-title">Account Info</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Role</span>
                    <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="badge {{ $admin->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $admin->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Login</span>
                    <span class="info-value">{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value">{{ $admin->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection