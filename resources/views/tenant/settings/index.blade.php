@extends('layouts.tenant')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="admin-card">
            <h2 class="card-title">Business Information</h2>
            
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Company Name</label>
                        <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $tenant->company_name) }}" required>
                        @error('company_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $tenant->phone) }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">GST Number</label>
                        <input type="text" name="gst_number" class="form-input" value="{{ old('gst_number', $tenant->gst_number) }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Currency</label>
                        <select name="currency" class="form-select" required>
                            <option value="INR" {{ old('currency', $tenant->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                            <option value="USD" {{ old('currency', $tenant->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="AED" {{ old('currency', $tenant->currency) == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" rows="2">{{ old('address', $tenant->address) }}</textarea>
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
                    
                    <div class="form-group">
                        <label class="form-label required">Country</label>
                        <select name="country" class="form-select" required>
                            <option value="IN" {{ old('country', $tenant->country) == 'IN' ? 'selected' : '' }}>India</option>
                            <option value="AE" {{ old('country', $tenant->country) == 'AE' ? 'selected' : '' }}>UAE</option>
                            <option value="US" {{ old('country', $tenant->country) == 'US' ? 'selected' : '' }}>United States</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Company Logo</label>
                    <div class="flex items-center gap-4">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="w-20 h-20 rounded-xl object-cover" id="logoPreview">
                        @else
                            <div class="w-20 h-20 bg-white/5 rounded-xl flex items-center justify-center" id="logoPreview">
                                <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <input type="file" name="logo" id="logoInput" class="hidden" accept="image/*">
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('logoInput').click()">
                                <i data-lucide="upload"></i> Upload Logo
                            </button>
                            <p class="text-xs text-gray-400 mt-2">Max 2MB. Appears on invoices.</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="space-y-6">
        <div class="admin-card">
            <h2 class="card-title">Subscription</h2>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Current Plan</span>
                    <span class="badge badge-primary text-lg px-4 py-2">{{ ucfirst($tenant->plan) }}</span>
                </div>
                @if($tenant->trial_ends_at)
                    <div class="info-item">
                        <span class="info-label">Trial Ends</span>
                        <span class="info-value {{ $tenant->trial_ends_at->isPast() ? 'text-red-400' : '' }}">
                            {{ $tenant->trial_ends_at->format('M d, Y') }}
                        </span>
                    </div>
                @endif
            </div>
            <a href="{{ route('settings.billing') }}" class="btn btn-outline btn-block mt-4">
                <i data-lucide="credit-card"></i> Manage Billing
            </a>
        </div>
        
        <div class="admin-card">
            <h2 class="card-title">Quick Links</h2>
            <div class="space-y-2">
                <a href="{{ route('settings.invoice') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Invoice Settings</span>
                </a>
                <a href="{{ route('team.index') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Team Members</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('logoInput').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.outerHTML = `<img src="${e.target.result}" alt="Logo" class="w-20 h-20 rounded-xl object-cover" id="logoPreview">`;
            }
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection