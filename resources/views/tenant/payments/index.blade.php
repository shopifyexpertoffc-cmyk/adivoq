@extends('layouts.tenant')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="wallet"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_received']) }}</div>
        <div class="stat-card-label">Total Received</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="calendar"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['this_month']) }}</div>
        <div class="stat-card-label">This Month</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="trending-up"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['last_month']) }}</div>
        <div class="stat-card-label">Last Month</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('payments.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search payments..." value="{{ request('search') }}">
        </div>
        
        <select name="brand_id" class="filter-select" onchange="this.form.submit()">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        
        <select name="payment_method" class="filter-select" onchange="this.form.submit()">
            <option value="">All Methods</option>
            <option value="Bank Transfer" {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="UPI" {{ request('payment_method') == 'UPI' ? 'selected' : '' }}>UPI</option>
            <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
            <option value="Cheque" {{ request('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
        </select>
        
        <input type="date" name="from_date" class="form-input" style="width: auto;" value="{{ request('from_date') }}" onchange="this.form.submit()">
        <input type="date" name="to_date" class="form-input" style="width: auto;" value="{{ request('to_date') }}" onchange="this.form.submit()">
    </form>
</div>

<!-- Payments Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Brand</th>
                    <th>Invoice</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>
                            @if($payment->brand)
                                <a href="{{ route('brands.show', $payment->brand) }}" class="hover:text-primary">
                                    {{ $payment->brand->name }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->invoice)
                                <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-primary hover:underline">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-green-400 font-semibold">
                                +{{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ $payment->payment_method ?? 'Unknown' }}
                            </span>
                        </td>
                        <td class="font-mono text-sm text-gray-400">
                            {{ $payment->transaction_id ?? '-' }}
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('payments.show', $payment) }}" class="table-action-btn" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Delete this payment? This will revert invoice amounts.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-action-btn danger" title="Delete">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="wallet"></i>
                                </div>
                                <h3 class="empty-state-title">No payments recorded</h3>
                                <p class="empty-state-text">Payments will appear here when you mark invoices as paid.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($payments->hasPages())
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection