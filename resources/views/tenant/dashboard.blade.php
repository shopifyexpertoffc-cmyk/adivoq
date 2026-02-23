@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon green">
                <i data-lucide="indian-rupee"></i>
            </div>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['current_month_revenue']) }}</div>
        <div class="stat-card-label">This Month</div>
        <div class="stat-card-change {{ $stats['revenue_change'] >= 0 ? 'positive' : 'negative' }}">
            <i data-lucide="{{ $stats['revenue_change'] >= 0 ? 'trending-up' : 'trending-down' }}"></i>
            {{ abs($stats['revenue_change']) }}% vs last month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon orange">
                <i data-lucide="clock"></i>
            </div>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['pending_amount']) }}</div>
        <div class="stat-card-label">Pending Payments</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon blue">
                <i data-lucide="building-2"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['total_clients'] }}</div>
        <div class="stat-card-label">Active Clients</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-icon purple">
                <i data-lucide="briefcase"></i>
            </div>
        </div>
        <div class="stat-card-value">{{ $stats['active_campaigns'] }}</div>
        <div class="stat-card-label">Active Campaigns</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card mb-6">
    <div class="flex items-center justify-between">
        <h3 class="font-semibold">Quick Actions</h3>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
        <a href="{{ route('invoices.create') }}" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="file-plus" class="w-5 h-5 text-green-400"></i>
            </div>
            <span>New Invoice</span>
        </a>
        <a href="{{ route('campaigns.create') }}" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="briefcase" class="w-5 h-5 text-blue-400"></i>
            </div>
            <span>New Campaign</span>
        </a>
        <a href="{{ route('brands.create') }}" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5 text-purple-400"></i>
            </div>
            <span>Add Brand</span>
        </a>
        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-orange-400"></i>
            </div>
            <span>View Reports</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Invoices -->
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Recent Invoices</h3>
            <a href="{{ route('invoices.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        
        @if($recentInvoices->count() > 0)
            <div class="space-y-3">
                @foreach($recentInvoices as $invoice)
                    <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-gray-400">{{ $invoice->brand->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">₹{{ number_format($invoice->total_amount) }}</p>
                            <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'info' : 'secondary') }} text-xs">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                <p>No invoices yet</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm mt-3">Create First Invoice</a>
            </div>
        @endif
    </div>
    
    <!-- Recent Payments -->
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Recent Payments</h3>
            <a href="{{ route('payments.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        
        @if($recentPayments->count() > 0)
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ $payment->brand->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-400">{{ $payment->payment_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <p class="font-semibold text-green-400">+₹{{ number_format($payment->amount) }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i data-lucide="wallet" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                <p>No payments recorded yet</p>
            </div>
        @endif
    </div>
</div>

<!-- Overdue Invoices Alert -->
@if($overdueInvoices->count() > 0)
    <div class="admin-card mt-6 border-red-500/30">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-400"></i>
            </div>
            <div>
                <h3 class="font-semibold text-red-400">Overdue Invoices</h3>
                <p class="text-sm text-gray-400">{{ $overdueInvoices->count() }} invoice(s) require attention</p>
            </div>
        </div>
        
        <div class="space-y-3">
            @foreach($overdueInvoices as $invoice)
                <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center justify-between p-3 bg-red-500/10 rounded-xl hover:bg-red-500/20 transition">
                    <div>
                        <p class="font-medium">{{ $invoice->invoice_number }} - {{ $invoice->brand->name }}</p>
                        <p class="text-sm text-red-400">Due {{ $invoice->due_date->diffForHumans() }}</p>
                    </div>
                    <p class="font-semibold">₹{{ number_format($invoice->balance_amount) }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif

<!-- Upcoming Deadlines -->
@if($upcomingDeadlines->count() > 0)
    <div class="admin-card mt-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i data-lucide="calendar" class="w-5 h-5 text-yellow-400"></i>
            </div>
            <div>
                <h3 class="font-semibold">Upcoming Deadlines</h3>
                <p class="text-sm text-gray-400">Next 7 days</p>
            </div>
        </div>
        
        <div class="space-y-3">
            @foreach($upcomingDeadlines as $campaign)
                <a href="{{ route('campaigns.show', $campaign) }}" class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <div>
                        <p class="font-medium">{{ $campaign->name }}</p>
                        <p class="text-sm text-gray-400">{{ $campaign->brand->name ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-yellow-400">{{ $campaign->deliverable_date->format('M d') }}</p>
                        <p class="text-xs text-gray-400">{{ $campaign->deliverable_date->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection