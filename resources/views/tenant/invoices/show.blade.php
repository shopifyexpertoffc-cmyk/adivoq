@extends('layouts.tenant')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page-title', 'Invoice Details')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1">
        <div class="admin-card">
            <!-- Invoice Header -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold">INVOICE</h2>
                    <p class="text-xl text-primary">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="text-right">
                    @php
                        $tenant = tenant();
                    @endphp
                    @if($tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="w-20 h-20 rounded-xl ml-auto mb-2">
                    @endif
                    <p class="font-bold text-lg">{{ $tenant->company_name ?? $tenant->name }}</p>
                    @if($tenant->address)
                        <p class="text-sm text-gray-400">{{ $tenant->address }}</p>
                    @endif
                    @if($tenant->gst_number)
                        <p class="text-sm text-gray-400">GST: {{ $tenant->gst_number }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Status & Dates -->
            <div class="flex items-center gap-4 mb-6">
                @php
                    $isOverdue = $invoice->due_date->isPast() && !in_array($invoice->status, ['paid', 'cancelled']);
                @endphp
                @if($isOverdue)
                    <span class="badge badge-danger text-lg px-4 py-2">Overdue</span>
                @else
                    <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'info' : 'secondary') }} text-lg px-4 py-2">
                        {{ ucfirst($invoice->status) }}
                    </span>
                @endif
            </div>
            
            <!-- Bill To -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-sm text-gray-400 mb-2">Bill To</p>
                    <p class="font-semibold text-lg">{{ $invoice->client_name }}</p>
                    @if($invoice->client_email)
                        <p class="text-gray-400">{{ $invoice->client_email }}</p>
                    @endif
                    @if($invoice->client_address)
                        <p class="text-gray-400">{{ $invoice->client_address }}</p>
                    @endif
                    @if($invoice->client_gst)
                        <p class="text-gray-400">GST: {{ $invoice->client_gst }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="mb-3">
                        <p class="text-sm text-gray-400">Invoice Date</p>
                        <p class="font-medium">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-sm text-gray-400">Due Date</p>
                        <p class="font-medium {{ $isOverdue ? 'text-red-400' : '' }}">{{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                    @if($invoice->campaign)
                        <div>
                            <p class="text-sm text-gray-400">Campaign</p>
                            <a href="{{ route('campaigns.show', $invoice->campaign) }}" class="text-primary hover:underline">
                                {{ $invoice->campaign->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Items Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left py-3 text-gray-400 font-medium">Description</th>
                            <th class="text-center py-3 text-gray-400 font-medium">Qty</th>
                            <th class="text-right py-3 text-gray-400 font-medium">Rate</th>
                            <th class="text-right py-3 text-gray-400 font-medium">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $item['description'] }}</td>
                                <td class="py-4 text-center">{{ $item['quantity'] }}</td>
                                <td class="py-4 text-right">{{ $invoice->currency }} {{ number_format($item['rate'], 2) }}</td>
                                <td class="py-4 text-right font-medium">{{ $invoice->currency }} {{ number_format($item['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-80">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">Subtotal</span>
                        <span>{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount > 0)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-400">Discount {{ $invoice->discount_type === 'percent' ? '(' . $invoice->discount . '%)' : '' }}</span>
                            <span class="text-red-400">-{{ $invoice->currency }} {{ number_format($invoice->discount_type === 'percent' ? ($invoice->subtotal * $invoice->discount / 100) : $invoice->discount, 2) }}</span>
                        </div>
                    @endif
                    @if($invoice->tax_enabled && $invoice->tax_amount > 0)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-400">{{ strtoupper($invoice->tax_type) }} ({{ $invoice->tax_rate }}%)</span>
                            <span>{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($invoice->tds_applicable && $invoice->tds_amount > 0)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-400">TDS ({{ $invoice->tds_rate }}%)</span>
                            <span class="text-red-400">-{{ $invoice->currency }} {{ number_format($invoice->tds_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-3 border-t border-white/10 mt-2">
                        <span class="font-semibold text-lg">Total</span>
                        <span class="font-bold text-xl text-primary">{{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    @if($invoice->paid_amount > 0)
                        <div class="flex justify-between py-2 text-green-400">
                            <span>Paid</span>
                            <span>-{{ $invoice->currency }} {{ number_format($invoice->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2 font-semibold">
                            <span>Balance Due</span>
                            <span class="text-orange-400">{{ $invoice->currency }} {{ number_format($invoice->balance_amount, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Notes & Terms -->
            @if($invoice->notes || $invoice->terms)
                <div class="mt-8 pt-8 border-t border-white/10">
                    @if($invoice->notes)
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 mb-1">Notes</p>
                            <p class="whitespace-pre-wrap">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                    @if($invoice->terms)
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Terms & Conditions</p>
                            <p class="text-sm text-gray-400 whitespace-pre-wrap">{{ $invoice->terms }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Payments History -->
        @if($invoice->payments->count() > 0)
            <div class="admin-card mt-6">
                <h3 class="card-title">Payment History</h3>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Transaction ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="text-green-400">+{{ $invoice->currency }} {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                    <td class="font-mono text-sm">{{ $payment->transaction_id ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="w-full lg:w-80 space-y-6">
        <!-- Actions -->
        <div class="admin-card">
            <h3 class="card-title">Actions</h3>
            <div class="space-y-3">
                @if($invoice->status !== 'paid')
                    <button type="button" class="btn btn-primary btn-block" onclick="document.getElementById('markPaidModal').classList.add('active')">
                        <i data-lucide="check-circle"></i> Mark as Paid
                    </button>
                    @if($invoice->balance_amount > 0 && $invoice->balance_amount < $invoice->total_amount)
                        <button type="button" class="btn btn-secondary btn-block" onclick="document.getElementById('recordPaymentModal').classList.add('active')">
                            <i data-lucide="plus"></i> Record Payment
                        </button>
                    @endif
                @endif
                
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-outline btn-block">
                    <i data-lucide="download"></i> Download PDF
                </a>
                
                @if(in_array($invoice->status, ['draft', 'sent', 'viewed']))
                    <form action="{{ route('invoices.send', $invoice) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block">
                            <i data-lucide="mail"></i> Send via Email
                        </button>
                    </form>
                    
                    @if($invoice->brand->phone)
                        <form action="{{ route('invoices.send-whatsapp', $invoice) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-block">
                                <i data-lucide="message-circle"></i> Send via WhatsApp
                            </button>
                        </form>
                    @endif
                @endif
                
                @if($invoice->status !== 'paid')
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline btn-block">
                        <i data-lucide="edit"></i> Edit Invoice
                    </a>
                @endif
                
                <a href="{{ route('invoices.public', $invoice->invoice_number) }}" target="_blank" class="btn btn-outline btn-block">
                    <i data-lucide="external-link"></i> Client View
                </a>
            </div>
        </div>
        
        <!-- Info -->
        <div class="admin-card">
            <h3 class="card-title">Info</h3>
            <div class="space-y-4">
                @if($invoice->sent_at)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <i data-lucide="send" class="w-4 h-4 text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-sm">Sent</p>
                            <p class="text-xs text-gray-400">{{ $invoice->sent_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($invoice->viewed_at)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                            <i data-lucide="eye" class="w-4 h-4 text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-sm">Viewed by Client</p>
                            <p class="text-xs text-gray-400">{{ $invoice->viewed_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($invoice->paid_at)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-sm">Paid</p>
                            <p class="text-xs text-gray-400">{{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="admin-modal-overlay" id="markPaidModal">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Mark Invoice as Paid</h3>
            <button type="button" class="admin-modal-close" onclick="document.getElementById('markPaidModal').classList.remove('active')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST">
            @csrf
            <div class="admin-modal-body">
                <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6">
                    <p class="text-green-400">
                        Amount to be recorded: <strong>{{ $invoice->currency }} {{ number_format($invoice->balance_amount, 2) }}</strong>
                    </p>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Payment Date</label>
                    <input type="date" name="payment_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">Select method</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="UPI">UPI</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Stripe">Stripe</option>
                        <option value="Razorpay">Razorpay</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction ID / Reference</label>
                    <input type="text" name="transaction_id" class="form-input" placeholder="Optional">
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('markPaidModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Mark as Paid</button>
            </div>
        </form>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="admin-modal-overlay" id="recordPaymentModal">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Record Payment</h3>
            <button type="button" class="admin-modal-close" onclick="document.getElementById('recordPaymentModal').classList.remove('active')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('invoices.record-payment', $invoice) }}" method="POST">
            @csrf
            <div class="admin-modal-body">
                <div class="bg-white/5 rounded-xl p-4 mb-6">
                    <p class="text-gray-400">
                        Balance Due: <strong class="text-white">{{ $invoice->currency }} {{ number_format($invoice->balance_amount, 2) }}</strong>
                    </p>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Amount Received</label>
                    <input type="number" name="amount" class="form-input" step="0.01" min="0.01" max="{{ $invoice->balance_amount }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Payment Date</label>
                    <input type="date" name="payment_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">Select method</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="UPI">UPI</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction ID</label>
                    <input type="text" name="transaction_id" class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-textarea" rows="2"></textarea>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('recordPaymentModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection