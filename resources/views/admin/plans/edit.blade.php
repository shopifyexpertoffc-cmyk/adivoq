@extends('layouts.admin')

@section('title', 'Edit Plan')
@section('page-title', 'Edit Plan: ' . $plan->name)

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Plan Information</h2>
        
        <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Plan Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $plan->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-select">
                        <option value="INR" {{ $plan->currency == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                        <option value="USD" {{ $plan->currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="AED" {{ $plan->currency == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="2">{{ old('description', $plan->description) }}</textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Monthly Price</label>
                    <input type="number" step="0.01" name="price_monthly" class="form-input" value="{{ old('price_monthly', $plan->price_monthly) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Yearly Price</label>
                    <input type="number" step="0.01" name="price_yearly" class="form-input" value="{{ old('price_yearly', $plan->price_yearly) }}" required>
                </div>
            </div>
            
            <h3 class="font-semibold mt-8 mb-4">Plan Limits</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Invoices per Month</label>
                    <input type="number" name="max_invoices_per_month" class="form-input" value="{{ old('max_invoices_per_month', $plan->max_invoices_per_month) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Max Brands</label>
                    <input type="number" name="max_brands" class="form-input" value="{{ old('max_brands', $plan->max_brands) }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Max Campaigns</label>
                    <input type="number" name="max_campaigns" class="form-input" value="{{ old('max_campaigns', $plan->max_campaigns) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Max Team Members</label>
                    <input type="number" name="max_team_members" class="form-input" value="{{ old('max_team_members', $plan->max_team_members) }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Trial Days</label>
                    <input type="number" name="trial_days" class="form-input" value="{{ old('trial_days', $plan->trial_days) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', $plan->sort_order) }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}>
                    <span>Featured Plan (Highlighted)</span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i> Update Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection