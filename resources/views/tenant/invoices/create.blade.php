@extends('layouts.tenant')

@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('content')
<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Header -->
            <div class="admin-card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold">Invoice</h2>
                        <p class="text-gray-400">{{ $invoiceNumber }}</p>
                    </div>
                    <div class="text-right">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="w-16 h-16 rounded-xl ml-auto">
                        @endif
                        <p class="font-semibold mt-2">{{ $tenant->company_name ?? $tenant->name }}</p>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Brand</label>
                        <select name="brand_id" id="brandSelect" class="form-select" required onchange="loadBrandDetails()">
                            <option value="">Select brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" 
                                    data-name="{{ $brand->name }}"
                                    data-email="{{ $brand->email }}"
                                    data-address="{{ $brand->address }}"
                                    data-gst="{{ $brand->gst_number }}"
                                    {{ old('brand_id', request('brand_id')) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    @if($campaigns->count() > 0)
                    <div class="form-group">
                        <label class="form-label">Campaign (Optional)</label>
                        <select name="campaign_id" class="form-select">
                            <option value="">Select campaign</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ old('campaign_id', request('campaign_id')) == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }} ({{ $campaign->currency }} {{ number_format($campaign->total_amount) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                
                @if($milestone)
                    <input type="hidden" name="milestone_id" value="{{ $milestone->id }}">
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4 mt-4">
                        <p class="text-blue-400 text-sm">
                            <i data-lucide="info" class="w-4 h-4 inline"></i>
                            Creating invoice for milestone: <strong>{{ $milestone->title }}</strong> ({{ $milestone->campaign->currency }} {{ number_format($milestone->amount) }})
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Client Details -->
            <div class="admin-card">
                <h3 class="card-title">Bill To</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Client Name</label>
                        <input type="text" name="client_name" id="clientName" class="form-input" value="{{ old('client_name', $milestone?->campaign?->brand?->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client Email</label>
                        <input type="email" name="client_email" id="clientEmail" class="form-input" value="{{ old('client_email', $milestone?->campaign?->brand?->email) }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client Address</label>
                        <textarea name="client_address" id="clientAddress" class="form-textarea" rows="2">{{ old('client_address', $milestone?->campaign?->brand?->address) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client GST Number</label>
                        <input type="text" name="client_gst" id="clientGst" class="form-input" value="{{ old('client_gst', $milestone?->campaign?->brand?->gst_number) }}">
                    </div>
                </div>
            </div>
            
            <!-- Invoice Items -->
            <div class="admin-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Invoice Items</h3>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addItem()">
                        <i data-lucide="plus"></i> Add Item
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full" id="itemsTable">
                        <thead>
                            <tr class="text-left text-sm text-gray-400">
                                <th class="pb-3" style="width: 50%">Description</th>
                                <th class="pb-3 text-center" style="width: 15%">Qty</th>
                                <th class="pb-3 text-right" style="width: 20%">Rate</th>
                                <th class="pb-3 text-right" style="width: 15%">Amount</th>
                                <th class="pb-3" style="width: 40px"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr class="item-row" id="item-0">
                                <td class="py-2 pr-2">
                                    <input type="text" name="items[0][description]" class="form-input" placeholder="Service description" value="{{ $milestone ? $milestone->title : '' }}" required>
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" name="items[0][quantity]" class="form-input text-center item-qty" value="1" min="1" step="1" required onchange="calculateTotals()">
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" name="items[0][rate]" class="form-input text-right item-rate" placeholder="0.00" value="{{ $milestone ? $milestone->amount : '' }}" step="0.01" min="0" required onchange="calculateTotals()">
                                </td>
                                <td class="py-2 px-2 text-right item-amount font-semibold">
                                    {{ $milestone ? number_format($milestone->amount, 2) : '0.00' }}
                                </td>
                                <td class="py-2 pl-2">
                                    <button type="button" class="text-red-400 hover:text-red-300 opacity-50" disabled>
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Notes & Terms -->
            <div class="admin-card">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-textarea" rows="3" placeholder="Any notes for the client...">{{ old('notes') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Terms & Conditions</label>
                        <textarea name="terms" class="form-textarea" rows="3" placeholder="Payment terms...">{{ old('terms', 'Payment is due within the specified due date. Late payments may incur additional charges.') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Invoice Details -->
            <div class="admin-card">
                <h3 class="card-title">Invoice Details</h3>
                
                <div class="form-group">
                    <label class="form-label required">Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-input" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Due Date</label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
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
            
            <!-- Tax Settings -->
            <div class="admin-card">
                <h3 class="card-title">Tax Settings</h3>
                
                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" name="tax_enabled" value="1" id="taxEnabled" {{ old('tax_enabled', true) ? 'checked' : '' }} onchange="calculateTotals()">
                        <span>Apply Tax (GST/VAT)</span>
                    </label>
                </div>
                
                <div id="taxSettings">
                    <div class="form-group">
                        <label class="form-label">Tax Type</label>
                        <select name="tax_type" class="form-select">
                            <option value="gst" {{ old('tax_type', 'gst') == 'gst' ? 'selected' : '' }}>GST</option>
                            <option value="vat" {{ old('tax_type') == 'vat' ? 'selected' : '' }}>VAT</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="taxRate" class="form-input" value="{{ old('tax_rate', 18) }}" step="0.01" min="0" max="100" onchange="calculateTotals()">
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <label class="form-checkbox">
                        <input type="checkbox" name="tds_applicable" value="1" id="tdsApplicable" {{ old('tds_applicable') ? 'checked' : '' }} onchange="calculateTotals()">
                        <span>TDS Applicable</span>
                    </label>
                </div>
                
                <div id="tdsSettings" class="hidden">
                    <div class="form-group">
                        <label class="form-label">TDS Rate (%)</label>
                        <input type="number" name="tds_rate" id="tdsRate" class="form-input" value="{{ old('tds_rate', 10) }}" step="0.01" min="0" max="100" onchange="calculateTotals()">
                    </div>
                </div>
            </div>
            
            <!-- Discount -->
            <div class="admin-card">
                <h3 class="card-title">Discount</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <input type="number" name="discount" id="discountAmount" class="form-input" value="{{ old('discount', 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select name="discount_type" id="discountType" class="form-select" onchange="calculateTotals()">
                            <option value="fixed" {{ old('discount_type', 'fixed') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Percent</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Totals -->
            <div class="admin-card">
                <h3 class="card-title">Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Subtotal</span>
                        <span id="subtotal">₹0.00</span>
                    </div>
                    <div class="flex justify-between" id="discountRow">
                        <span class="text-gray-400">Discount</span>
                        <span class="text-red-400" id="discountDisplay">-₹0.00</span>
                    </div>
                    <div class="flex justify-between" id="taxRow">
                        <span class="text-gray-400">Tax (<span id="taxRateDisplay">18</span>%)</span>
                        <span id="taxAmount">₹0.00</span>
                    </div>
                    <div class="flex justify-between hidden" id="tdsRow">
                        <span class="text-gray-400">TDS (<span id="tdsRateDisplay">10</span>%)</span>
                        <span class="text-red-400" id="tdsAmount">-₹0.00</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-white/10">
                        <span class="font-semibold">Total</span>
                        <span class="text-xl font-bold text-primary" id="totalAmount">₹0.00</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="admin-card">
                <div class="space-y-3">
                    <button type="submit" name="status" value="draft" class="btn btn-secondary btn-block">
                        <i data-lucide="save"></i> Save as Draft
                    </button>
                    <button type="submit" name="status" value="sent" class="btn btn-primary btn-block">
                        <i data-lucide="send"></i> Save & Send
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline btn-block">
                        <i data-lucide="x"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let itemCount = 1;

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const index = itemCount++;
    
    const html = `
        <tr class="item-row" id="item-${index}">
            <td class="py-2 pr-2">
                <input type="text" name="items[${index}][description]" class="form-input" placeholder="Service description" required>
            </td>
            <td class="py-2 px-2">
                <input type="number" name="items[${index}][quantity]" class="form-input text-center item-qty" value="1" min="1" step="1" required onchange="calculateTotals()">
            </td>
            <td class="py-2 px-2">
                <input type="number" name="items[${index}][rate]" class="form-input text-right item-rate" placeholder="0.00" step="0.01" min="0" required onchange="calculateTotals()">
            </td>
            <td class="py-2 px-2 text-right item-amount font-semibold">0.00</td>
            <td class="py-2 pl-2">
                <button type="button" class="text-red-400 hover:text-red-300" onclick="removeItem(${index})">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', html);
    lucide.createIcons();
}

function removeItem(index) {
    document.getElementById(`item-${index}`).remove();
    calculateTotals();
}

function loadBrandDetails() {
    const select = document.getElementById('brandSelect');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('clientName').value = option.dataset.name || '';
        document.getElementById('clientEmail').value = option.dataset.email || '';
        document.getElementById('clientAddress').value = option.dataset.address || '';
        document.getElementById('clientGst').value = option.dataset.gst || '';
    }
}

function calculateTotals() {
    // Calculate subtotal
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
        const rate = parseFloat(row.querySelector('.item-rate')?.value) || 0;
        const amount = qty * rate;
        subtotal += amount;
        
        const amountCell = row.querySelector('.item-amount');
        if (amountCell) {
            amountCell.textContent = amount.toFixed(2);
        }
    });
    
    // Discount
    let discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const discountType = document.getElementById('discountType').value;
    if (discountType === 'percent') {
        discount = (subtotal * discount) / 100;
    }
    
    // Taxable amount
    const taxableAmount = subtotal - discount;
    
    // Tax
    let taxAmount = 0;
    const taxEnabled = document.getElementById('taxEnabled').checked;
    const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
    if (taxEnabled) {
        taxAmount = (taxableAmount * taxRate) / 100;
    }
    
    // TDS
    let tdsAmount = 0;
    const tdsApplicable = document.getElementById('tdsApplicable').checked;
    const tdsRate = parseFloat(document.getElementById('tdsRate').value) || 0;
    if (tdsApplicable) {
        tdsAmount = (taxableAmount * tdsRate) / 100;
    }
    
    // Total
    const total = taxableAmount + taxAmount - tdsAmount;
    
    // Update display
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').textContent = '-₹' + discount.toFixed(2);
    document.getElementById('taxRateDisplay').textContent = taxRate;
    document.getElementById('taxAmount').textContent = '₹' + taxAmount.toFixed(2);
    document.getElementById('tdsRateDisplay').textContent = tdsRate;
    document.getElementById('tdsAmount').textContent = '-₹' + tdsAmount.toFixed(2);
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);
    
    // Show/hide rows
    document.getElementById('taxRow').style.display = taxEnabled ? 'flex' : 'none';
    document.getElementById('taxSettings').style.display = taxEnabled ? 'block' : 'none';
    document.getElementById('tdsRow').style.display = tdsApplicable ? 'flex' : 'none';
    document.getElementById('tdsSettings').style.display = tdsApplicable ? 'block' : 'none';
    document.getElementById('discountRow').style.display = discount > 0 ? 'flex' : 'none';
}

// Initial calculation
document.addEventListener('DOMContentLoaded', function() {
    loadBrandDetails();
    calculateTotals();
});
</script>
@endpush
@endsection