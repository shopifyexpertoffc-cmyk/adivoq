@extends('layouts.admin')

@section('title', 'Create Plan')
@section('page-title', 'Create New Plan')

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Plan Information</h2>
        
        <form action="{{ route('admin.plans.store') }}" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Plan Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-select">
                        <option value="INR">INR (₹)</option>
                        <option value="USD">USD ($)</option>
                        <option value="AED">AED (د.إ)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="2">{{ old('description') }}</textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Monthly Price</label>
                    <input type="number" step="0.01" name="price_monthly" class="form-input" value="{{ old('price_monthly', 0) }}" required>
                    <span class="form-hint">Set to 0 for free plan</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Yearly Price</label>
                    <input type="number" step="0.01" name="price_yearly" class="form-input" value="{{ old('price_yearly', 0) }}" required>
                </div>
            </div>
            
            <h3 class="font-semibold mt-8 mb-4">Plan Limits</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Invoices per Month</label>
                    <input type="number" name="max_invoices_per_month" class="form-input" value="{{ old('max_invoices_per_month', 5) }}" required>
                    <span class="form-hint">Use -1 for unlimited</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Max Brands</label>
                    <input type="number" name="max_brands" class="form-input" value="{{ old('max_brands', 10) }}" required>
                    <span class="form-hint">Use -1 for unlimited</span>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Max Campaigns</label>
                    <input type="number" name="max_campaigns" class="form-input" value="{{ old('max_campaigns', 10) }}" required>
                    <span class="form-hint">Use -1 for unlimited</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Max Team Members</label>
                    <input type="number" name="max_team_members" class="form-input" value="{{ old('max_team_members', 1) }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Trial Days</label>
                    <input type="number" name="trial_days" class="form-input" value="{{ old('trial_days', 14) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Featured Plan (Highlighted)</span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="plus"></i> Create Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection