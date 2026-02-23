@extends('layouts.tenant')

@section('title', $brand->name)
@section('page-title', $brand->name)

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1">
        <!-- Brand Header -->
        <div class="admin-card mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-20 h-20 rounded-2xl object-cover">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($brand->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold">{{ $brand->name }}</h2>
                        @if($brand->industry)
                            <span class="badge badge-secondary">{{ $brand->industry }}</span>
                        @endif
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-400">
                            @if($brand->contact_person)
                                <span class="flex items-center gap-1">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                    {{ $brand->contact_person }}
                                </span>
                            @endif
                            @if($brand->email)
                                <a href="mailto:{{ $brand->email }}" class="flex items-center gap-1 hover:text-primary">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    {{ $brand->email }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-secondary btn-sm">
                        <i data-lucide="edit"></i> Edit
                    </a>
                    <a href="{{ route('campaigns.create', ['brand_id' => $brand->id]) }}" class="btn btn-primary btn-sm">
                        <i data-lucide="plus"></i> New Campaign
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="admin-card text-center">
                <p class="text-2xl font-bold">{{ $stats['total_campaigns'] }}</p>
                <p class="text-sm text-gray-400">Total Campaigns</p>
            </div>
            <div class="admin-card text-center">
                <p class="text-2xl font-bold">{{ $stats['active_campaigns'] }}</p>
                <p class="text-sm text-gray-400">Active</p>
            </div>
            <div class="admin-card text-center">
                <p class="text-2xl font-bold text-green-400">₹{{ number_format($stats['total_paid']) }}</p>
                <p class="text-sm text-gray-400">Total Paid</p>
            </div>
            <div class="admin-card text-center">
                <p class="text-2xl font-bold text-orange-400">₹{{ number_format($stats['pending_amount']) }}</p>
                <p class="text-sm text-gray-400">Pending</p>
            </div>
        </div>
        
        <!-- Recent Campaigns -->
        <div class="admin-card mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Recent Campaigns</h3>
                <a href="{{ route('campaigns.index', ['brand_id' => $brand->id]) }}" class="text-sm text-primary hover:underline">View all</a>
            </div>
            
            @if($brand->campaigns->count() > 0)
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->campaigns as $campaign)
                                <tr>
                                    <td>
                                        <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium hover:text-primary">
                                            {{ $campaign->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $campaign->status === 'completed' ? 'success' : ($campaign->status === 'in_progress' ? 'info' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $campaign->status)) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($campaign->total_amount) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $campaign->payment_status === 'completed' ? 'success' : ($campaign->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($campaign->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No campaigns yet</p>
            @endif
        </div>
        
        <!-- Recent Invoices -->
        <div class="admin-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Recent Invoices</h3>
                <a href="{{ route('invoices.index', ['brand_id' => $brand->id]) }}" class="text-sm text-primary hover:underline">View all</a>
            </div>
            
            @if($brand->invoices->count() > 0)
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->invoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="font-medium hover:text-primary">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-gray-400">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td>₹{{ number_format($invoice->total_amount) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 text-center py-4">No invoices yet</p>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="w-full lg:w-80">
        <div class="admin-card mb-6">
            <h3 class="card-title">Contact Details</h3>
            <div class="space-y-4">
                @if($brand->phone)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center">
                            <i data-lucide="phone" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Phone</p>
                            <a href="tel:{{ $brand->phone }}" class="font-medium hover:text-primary">{{ $brand->phone }}</a>
                        </div>
                    </div>
                @endif
                
                @if($brand->website)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center">
                            <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Website</p>
                            <a href="{{ $brand->website }}" target="_blank" class="font-medium hover:text-primary">{{ parse_url($brand->website, PHP_URL_HOST) }}</a>
                        </div>
                    </div>
                @endif
                
                @if($brand->gst_number)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">GST Number</p>
                            <p class="font-medium font-mono">{{ $brand->gst_number }}</p>
                        </div>
                    </div>
                @endif
                
                @if($brand->address)
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Address</p>
                            <p class="font-medium">{{ $brand->address }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        @if($brand->notes)
            <div class="admin-card">
                <h3 class="card-title">Notes</h3>
                <p class="text-gray-400 whitespace-pre-wrap">{{ $brand->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection