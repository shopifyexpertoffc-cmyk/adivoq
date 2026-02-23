@extends('layouts.tenant')

@section('title', 'New Campaign')
@section('page-title', 'Create Campaign')

@section('content')
<form action="{{ route('campaigns.store') }}" method="POST" id="campaignForm">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="admin-card">
                <h2 class="card-title">Campaign Details</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Brand</label>
                        <select name="brand_id" class="form-select" required>
                            <option value="">Select brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $selectedBrand?->id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Campaign Name</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="e.g., Instagram Reel - March 2024" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="3" placeholder="Campaign details, deliverables, etc.">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Platform</label>
                        <select name="platform" class="form-select">
                            <option value="">Select platform</option>
                            <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="YouTube" {{ old('platform') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            <option value="Twitter" {{ old('platform') == 'Twitter' ? 'selected' : '' }}>Twitter / X</option>
                            <option value="LinkedIn" {{ old('platform') == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                            <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="TikTok" {{ old('platform') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                            <option value="Blog" {{ old('platform') == 'Blog' ? 'selected' : '' }}>Blog</option>
                            <option value="Multiple" {{ old('platform') == 'Multiple' ? 'selected' : '' }}>Multiple Platforms</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Campaign Type</label>
                        <select name="campaign_type" class="form-select">
                            <option value="">Select type</option>
                            <option value="Sponsored" {{ old('campaign_type') == 'Sponsored' ? 'selected' : '' }}>Sponsored Post</option>
                            <option value="Barter" {{ old('campaign_type') == 'Barter' ? 'selected' : '' }}>Barter</option>
                            <option value="Affiliate" {{ old('campaign_type') == 'Affiliate' ? 'selected' : '' }}>Affiliate</option>
                            <option value="Brand Ambassador" {{ old('campaign_type') == 'Brand Ambassador' ? 'selected' : '' }}>Brand Ambassador</option>
                            <option value="Event" {{ old('campaign_type') == 'Event' ? 'selected' : '' }}>Event</option>
                            <option value="UGC" {{ old('campaign_type') == 'UGC' ? 'selected' : '' }}>UGC</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Financials -->
            <div class="admin-card">
                <h2 class="card-title">Financials</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Total Amount</label>
                        <div class="flex">
                            <select name="currency" class="form-select" style="width: 100px; border-radius: 10px 0 0 10px; border-right: none;">
                                <option value="INR" {{ old('currency', 'INR') == 'INR' ? 'selected' : '' }}>₹ INR</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>$ USD</option>
                                <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>د.إ AED</option>
                            </select>
                            <input type="number" name="total_amount" class="form-input" style="border-radius: 0 10px 10px 0;" value="{{ old('total_amount') }}" placeholder="50000" step="0.01" min="0" required>
                        </div>
                        @error('total_amount')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Advance Amount</label>
                        <input type="number" name="advance_amount" class="form-input" value="{{ old('advance_amount') }}" placeholder="0" step="0.01" min="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Agency Commission (%)</label>
                        <input type="number" name="agency_commission_percent" class="form-input" value="{{ old('agency_commission_percent', 0) }}" placeholder="0" step="0.01" min="0" max="100">
                        <span class="form-hint">If working through an agency</span>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Manager Commission (%)</label>
                        <input type="number" name="manager_commission_percent" class="form-input" value="{{ old('manager_commission_percent', 0) }}" placeholder="0" step="0.01" min="0" max="100">
                    </div>
                </div>
            </div>
            
            <!-- Dates -->
            <div class="admin-card">
                <h2 class="card-title">Timeline</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-input" value="{{ old('start_date') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deliverable Date</label>
                        <input type="date" name="deliverable_date" class="form-input" value="{{ old('deliverable_date') }}">
                        <span class="form-hint">When content needs to be delivered</span>
                    </div>
                </div>
            </div>
            
            <!-- Milestones -->
            <div class="admin-card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold">Milestones</h2>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addMilestone()">
                        <i data-lucide="plus"></i> Add Milestone
                    </button>
                </div>
                
                <p class="text-sm text-gray-400 mb-4">Break down the campaign into payment milestones (optional)</p>
                
                <div id="milestonesContainer">
                    <!-- Milestones will be added here -->
                </div>
                
                <div id="noMilestones" class="text-center py-8 text-gray-400 border border-dashed border-white/10 rounded-xl">
                    <i data-lucide="flag" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    <p>No milestones added</p>
                    <button type="button" class="btn btn-outline btn-sm mt-3" onclick="addMilestone()">
                        <i data-lucide="plus"></i> Add First Milestone
                    </button>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="admin-card">
                <h2 class="card-title">Additional Notes</h2>
                <textarea name="notes" class="form-textarea" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="card-title">Status</h2>
                
                <div class="form-group">
                    <label class="form-label required">Campaign Status</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>
            
            <div class="admin-card">
                <h2 class="card-title">Actions</h2>
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i data-lucide="save"></i> Create Campaign
                    </button>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary btn-block">
                        <i data-lucide="x"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let milestoneCount = 0;

function addMilestone() {
    const container = document.getElementById('milestonesContainer');
    const noMilestones = document.getElementById('noMilestones');
    noMilestones.style.display = 'none';
    
    const index = milestoneCount++;
    const html = `
        <div class="milestone-item bg-white/5 rounded-xl p-4 mb-3" id="milestone-${index}">
            <div class="flex items-center justify-between mb-3">
                <span class="font-medium">Milestone ${index + 1}</span>
                <button type="button" class="text-red-400 hover:text-red-300" onclick="removeMilestone(${index})">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="milestones[${index}][title]" class="form-input" placeholder="Milestone title" required>
                </div>
                <div>
                    <input type="number" name="milestones[${index}][amount]" class="form-input" placeholder="Amount" step="0.01" min="0" required>
                </div>
            </div>
            <div class="mt-3">
                <input type="date" name="milestones[${index}][due_date]" class="form-input">
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
    lucide.createIcons();
}

function removeMilestone(index) {
    const element = document.getElementById(`milestone-${index}`);
    element.remove();
    
    const container = document.getElementById('milestonesContainer');
    if (container.children.length === 0) {
        document.getElementById('noMilestones').style.display = 'block';
    }
}
</script>
@endpush
@endsection