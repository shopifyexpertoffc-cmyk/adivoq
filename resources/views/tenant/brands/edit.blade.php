@extends('layouts.tenant')

@section('title', 'Edit Brand')
@section('page-title', 'Edit: ' . $brand->name)

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">Brand Information</h2>
        
        <form action="{{ route('brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Brand Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $brand->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Industry</label>
                    <select name="industry" class="form-select">
                        <option value="">Select industry</option>
                        @foreach(['Fashion', 'Tech', 'Beauty', 'Food', 'Lifestyle', 'Health', 'Travel', 'Gaming', 'Finance', 'Education', 'Other'] as $ind)
                            <option value="{{ $ind }}" {{ old('industry', $brand->industry) == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person', $brand->contact_person) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $brand->email) }}">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $brand->phone) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-input" value="{{ old('website', $brand->website) }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">GST Number</label>
                <input type="text" name="gst_number" class="form-input" value="{{ old('gst_number', $brand->gst_number) }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-textarea" rows="2">{{ old('address', $brand->address) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Logo</label>
                @if($brand->logo)
                    <div class="flex items-center gap-4 mb-3">
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Current logo" class="w-16 h-16 rounded-xl object-cover">
                        <span class="text-sm text-gray-400">Current logo</span>
                    </div>
                @endif
                <input type="file" name="logo" class="form-input" accept="image/*">
                <span class="form-hint">Leave empty to keep current logo.</span>
            </div>
            
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea" rows="3">{{ old('notes', $brand->notes) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                    <i data-lucide="x"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i> Update Brand
                </button>
            </div>
        </form>
    </div>
</div>
@endsection