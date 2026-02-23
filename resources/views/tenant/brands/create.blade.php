@extends('layouts.tenant')

@section('title', 'Add Brand')
@section('page-title', 'Add New Brand')

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Brand Information</h2>
        
        <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Brand Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Industry</label>
                    <select name="industry" class="form-select">
                        <option value="">Select industry</option>
                        <option value="Fashion" {{ old('industry') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                        <option value="Tech" {{ old('industry') == 'Tech' ? 'selected' : '' }}>Technology</option>
                        <option value="Beauty" {{ old('industry') == 'Beauty' ? 'selected' : '' }}>Beauty</option>
                        <option value="Food" {{ old('industry') == 'Food' ? 'selected' : '' }}>Food & Beverage</option>
                        <option value="Lifestyle" {{ old('industry') == 'Lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                        <option value="Health" {{ old('industry') == 'Health' ? 'selected' : '' }}>Health & Fitness</option>
                        <option value="Travel" {{ old('industry') == 'Travel' ? 'selected' : '' }}>Travel</option>
                        <option value="Gaming" {{ old('industry') == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                        <option value="Finance" {{ old('industry') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Education" {{ old('industry') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Other" {{ old('industry') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person') }}" placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="contact@brand.com">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="+91 98765 43210">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-input" value="{{ old('website') }}" placeholder="https://brand.com">
                    @error('website')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">GST Number</label>
                <input type="text" name="gst_number" class="form-input" value="{{ old('gst_number') }}" placeholder="22AAAAA0000A1Z5">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-textarea" rows="2" placeholder="Full address...">{{ old('address') }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-input" accept="image/*">
                <span class="form-hint">Max 2MB. JPG, PNG only.</span>
                @error('logo')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="plus"></i> Add Brand
                </button>
            </div>
        </form>
    </div>
</div>
@endsection