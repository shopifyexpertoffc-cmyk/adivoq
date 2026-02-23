@extends('layouts.tenant')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="file-text"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_invoiced']) }}</div>
        <div class="stat-card-label">Total Invoiced</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_paid']) }}</div>
        <div class="stat-card-label">Total Received</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <i data-lucide="clock"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_pending']) }}</div>
        <div class="stat-card-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon red">
            <i data-lucide="alert-triangle"></i>
        </div>
        <div class="stat-card-value">{{ $stats['overdue_count'] }}</div>
        <div class="stat-card-label">Overdue</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form action="{{ route('invoices.index') }}" method="GET" class="flex gap-4 flex-wrap w-full">
        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search invoices..." value="{{ request('search') }}">
        </div>
        
        <select name="brand_id" class="filter-select" onchange="this.form.submit()">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="viewed" {{ request('status') == 'viewed' ? 'selected' : '' }}>Viewed</option>
            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>
        
        <input type="date" name="from_date" class="form-input" style="width: auto;" value="{{ request('from_date') }}" onchange="this.form.submit()">
        <input type="date" name="to_date" class="form-input" style="width: auto;" value="{{ request('to_date') }}" onchange="this.form.submit()">
        
        <a href="{{ route('invoices.create') }}" class="btn btn-primary ml-auto">
            <i data-lucide="plus"></i> New Invoice
        </a>
    </form>
</div>

<!-- Invoices Table -->
<div class="admin-card">
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}" class="font-medium hover:text-primary">
                                {{ $invoice->invoice_number }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                        </td>
                        <td>
                            <a href="{{ route('brands.show', $invoice->brand) }}" class="hover:text-primary">
                                {{ $invoice->brand->name }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $invoice->client_name }}</p>
                        </td>
                        <td>
                            <p class="font-semibold">{{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}</p>
                            @if($invoice->paid_amount > 0 && $invoice->paid_amount < $invoice->total_amount)
                                <p class="text-xs text-green-400">Paid: {{ number_format($invoice->paid_amount, 2) }}</p>
                            @endif
                        </td>
                        <td>
                            @php
                                $isOverdue = $invoice->due_date->isPast() && !in_array($invoice->status, ['paid', 'cancelled']);
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'sent' => 'info',
                                    'viewed' => 'primary',
                                    'partial' => 'warning',
                                    'paid' => 'success',
                                    'overdue' => 'danger',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            @if($isOverdue)
                                <span class="badge badge-danger">Overdue</span>
                            @else
                                <span class="badge badge-{{ $statusColors[$invoice->status] ?? 'secondary' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="{{ $isOverdue ? 'text-red-400' : 'text-gray-400' }}">
                            {{ $invoice->due_date->format('M d, Y') }}
                            @if($isOverdue)
                                <p class="text-xs">{{ $invoice->due_date->diffForHumans() }}</p>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('invoices.show', $invoice) }}" class="table-action-btn" title="View">
                                    <i data-lucide="eye"></i>
                                </a>
                                @if($invoice->status !== 'paid')
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="table-action-btn" title="Edit">
                                        <i data-lucide="edit"></i>
                                    </a>
                                @endif
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="table-action-btn" title="Download PDF" target="_blank">
                                    <i data-lucide="download"></i>
                                </a>
                                @if(in_array($invoice->status, ['draft', 'sent', 'viewed']))
                                    <form action="{{ route('invoices.send-whatsapp', $invoice) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="table-action-btn" title="Send via WhatsApp">
                                            <i data-lucide="message-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="file-text"></i>
                                </div>
                                <h3 class="empty-state-title">No invoices yet</h3>
                                <p class="empty-state-text">Create your first invoice to get started.</p>
                                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                    <i data-lucide="plus"></i> Create Invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
        <div class="mt-6">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection