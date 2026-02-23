@extends('layouts.admin')

@section('title', 'Create Tenant')
@section('page-title', 'Create New Tenant')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="admin-card">
            <h2 class="card-title">Tenant Information</h2>
            
            <form action="{{ route('admin.tenants.store') }}" method="POST">
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
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-input" value="{{ old('company_name') }}">
                        @error('company_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
    <label class="form-label required">Subdomain</label>
    <div class="flex items-center gap-2">
        <input
            type="text"
            name="domain"
            class="form-input"
            placeholder="mycompany"
            required
        >
        <span class="text-gray-400">.{{ $baseDomain }}</span>
    </div>
    <span class="form-hint">
        This will be the tenant's login URL (e.g. <strong>mycompany.{{ $baseDomain }}</strong>)
    </span>
</div>
                    
                    <div class="form-group">
                        <label class="form-label required">Plan</label>
                        <select name="plan" class="form-select" required>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->slug }}" {{ old('plan') == $plan->slug ? 'selected' : '' }}>
                                    {{ $plan->name }} - {{ $plan->isFree() ? 'Free' : '₹' . number_format($plan->price_monthly) . '/mo' }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Country</label>
                        <select name="country" class="form-select" required>
                            <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>UAE</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                        </select>
                        @error('country')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Currency</label>
                        <select name="currency" class="form-select" required>
                            <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        </select>
                        @error('currency')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                        <i data-lucide="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus"></i> Create Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div>
        <div class="admin-card">
            <h2 class="card-title">Quick Info</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Trial Period</span>
                    <span class="info-value">14 days</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Auto Setup</span>
                    <span class="badge badge-success">Enabled</span>
                </div>
            </div>
            <p class="text-gray-400 text-sm mt-4">
                A new database will be created for this tenant automatically. They will receive an email with login credentials.
            </p>
        </div>
    </div>
</div>
@endsection