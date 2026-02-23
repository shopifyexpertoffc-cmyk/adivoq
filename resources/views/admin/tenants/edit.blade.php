@extends('layouts.admin')

@section('title', 'Edit Tenant')
@section('page-title', 'Edit Tenant: ' . $tenant->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="admin-card">
            <h2 class="card-title">Tenant Information</h2>
            
            <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Full Name</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $tenant->name) }}" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Email Address</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $tenant->email) }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $tenant->phone) }}">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $tenant->company_name) }}">
                        @error('company_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-input" value="{{ old('gst_number', $tenant->gst_number) }}">
                        @error('gst_number')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Plan</label>
                        <select name="plan" class="form-select" required>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->slug }}" {{ old('plan', $tenant->plan) == $plan->slug ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" rows="2">{{ old('address', $tenant->address) }}</textarea>
                    @error('address')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input" value="{{ old('city', $tenant->city) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-input" value="{{ old('state', $tenant->state) }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Country</label>
                        <select name="country" class="form-select" required>
                            <option value="IN" {{ old('country', $tenant->country) == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="AE" {{ old('country', $tenant->country) == 'AE' ? 'selected' : '' }}>UAE</option>
                            <option value="US" {{ old('country', $tenant->country) == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="GB" {{ old('country', $tenant->country) == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Currency</label>
                        <select name="currency" class="form-select" required>
                            <option value="INR" {{ old('currency', $tenant->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="AED" {{ old('currency', $tenant->currency) == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                            <option value="USD" {{ old('currency', $tenant->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="GBP" {{ old('currency', $tenant->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                        <i data-lucide="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i> Update Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div>
        <div class="admin-card">
            <h2 class="card-title">Tenant Details</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Tenant ID</span>
                    <span class="info-value font-mono text-sm">{{ $tenant->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Domain</span>
                    <span class="info-value">{{ $tenant->domains->first()?->domain ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="badge badge-{{ $tenant->status == 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created</span>
                    <span class="info-value">{{ $tenant->created_at->format('M d, Y') }}</span>
                </div>
                @if($tenant->trial_ends_at)
                <div class="info-item">
                    <span class="info-label">Trial Ends</span>
                    <span class="info-value">{{ $tenant->trial_ends_at->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="admin-card mt-6">
            <h2 class="card-title">Danger Zone</h2>
            <p class="text-gray-400 text-sm mb-4">
                These actions are irreversible. Please be careful.
            </p>
            
            <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all tenant data permanently.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-block">
                    <i data-lucide="trash-2"></i> Delete Tenant
                </button>
            </form>
        </div>
    </div>
</div>
@endsection