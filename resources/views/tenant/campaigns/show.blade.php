@extends('layouts.tenant')

@section('title', $campaign->name)
@section('page-title', $campaign->name)

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content -->
    <div class="flex-1 space-y-6">
        <!-- Campaign Header -->
        <div class="admin-card">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'negotiation' => 'warning',
                                'confirmed' => 'info',
                                'in_progress' => 'primary',
                                'delivered' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$campaign->status] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $campaign->status)) }}
                        </span>
                        <span class="badge badge-{{ $campaign->payment_status === 'completed' ? 'success' : ($campaign->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                            Payment: {{ ucfirst($campaign->payment_status) }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold">{{ $campaign->name }}</h2>
                    <a href="{{ route('brands.show', $campaign->brand) }}" class="text-primary hover:underline">
                        {{ $campaign->brand->name }}
                    </a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-secondary btn-sm">
                        <i data-lucide="edit"></i> Edit
                    </a>
                    <a href="{{ route('invoices.create', ['campaign_id' => $campaign->id, 'brand_id' => $campaign->brand_id]) }}" class="btn btn-primary btn-sm">
                        <i data-lucide="file-plus"></i> Create Invoice
                    </a>
                </div>
            </div>
            
            @if($campaign->description)
                <div class="mt-4 pt-4 border-t border-white/10">
                    <p class="text-gray-400 whitespace-pre-wrap">{{ $campaign->description }}</p>
                </div>
            @endif
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-white/10">
                @if($campaign->platform)
                    <div>
                        <p class="text-sm text-gray-400">Platform</p>
                        <p class="font-medium">{{ $campaign->platform }}</p>
                    </div>
                @endif
                @if($campaign->campaign_type)
                    <div>
                        <p class="text-sm text-gray-400">Type</p>
                        <p class="font-medium">{{ $campaign->campaign_type }}</p>
                    </div>
                @endif
                @if($campaign->start_date)
                    <div>
                        <p class="text-sm text-gray-400">Start Date</p>
                        <p class="font-medium">{{ $campaign->start_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($campaign->deliverable_date)
                    <div>
                        <p class="text-sm text-gray-400">Deliverable Date</p>
                        <p class="font-medium {{ $campaign->deliverable_date->isPast() && $campaign->status !== 'completed' ? 'text-red-400' : '' }}">
                            {{ $campaign->deliverable_date->format('M d, Y') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Financial Summary -->
        <div class="admin-card">
            <h3 class="card-title">Financial Summary</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-400">Total Amount</p>
                    <p class="text-2xl font-bold">{{ $campaign->currency }} {{ number_format($campaign->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Paid Amount</p>
                    <p class="text-2xl font-bold text-green-400">{{ $campaign->currency }} {{ number_format($campaign->paid_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Balance</p>
                    <p class="text-2xl font-bold text-orange-400">{{ $campaign->currency }} {{ number_format($campaign->balance_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Progress</p>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span>{{ $campaign->payment_progress }}%</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-3">
                            <div class="bg-primary h-3 rounded-full" style="width: {{ $campaign->payment_progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($campaign->agency_commission_percent > 0 || $campaign->manager_commission_percent > 0)
                <div class="mt-6 pt-6 border-t border-white/10">
                    <h4 class="font-medium mb-3">Commission Breakdown</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($campaign->agency_commission_percent > 0)
                            <div>
                                <p class="text-sm text-gray-400">Agency Commission ({{ $campaign->agency_commission_percent }}%)</p>
                                <p class="font-semibold">{{ $campaign->currency }} {{ number_format($campaign->total_amount * $campaign->agency_commission_percent / 100, 2) }}</p>
                            </div>
                        @endif
                        @if($campaign->manager_commission_percent > 0)
                            <div>
                                <p class="text-sm text-gray-400">Manager Commission ({{ $campaign->manager_commission_percent }}%)</p>
                                <p class="font-semibold">{{ $campaign->currency }} {{ number_format($campaign->total_amount * $campaign->manager_commission_percent / 100, 2) }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-400">Net Amount</p>
                            @php
                                $totalCommission = ($campaign->agency_commission_percent + $campaign->manager_commission_percent) / 100;
                                $netAmount = $campaign->total_amount * (1 - $totalCommission);
                            @endphp
                            <p class="font-semibold text-green-400">{{ $campaign->currency }} {{ number_format($netAmount, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Milestones -->
        <div class="admin-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Milestones</h3>
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('addMilestoneModal').classList.add('active')">
                    <i data-lucide="plus"></i> Add Milestone
                </button>
            </div>
            
            @if($campaign->milestones->count() > 0)
                <div class="space-y-3">
                    @foreach($campaign->milestones as $milestone)
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $milestone->status === 'paid' ? 'bg-green-500/20' : 'bg-white/10' }}">
                                        @if($milestone->status === 'paid')
                                            <i data-lucide="check" class="w-5 h-5 text-green-400"></i>
                                        @else
                                            <i data-lucide="flag" class="w-5 h-5 text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $milestone->title }}</p>
                                        <div class="flex items-center gap-3 text-sm text-gray-400">
                                            <span>{{ $campaign->currency }} {{ number_format($milestone->amount, 2) }}</span>
                                            @if($milestone->due_date)
                                                <span>Due: {{ $milestone->due_date->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $milestoneStatusColors = [
                                            'pending' => 'secondary',
                                            'in_progress' => 'info',
                                            'submitted' => 'warning',
                                            'approved' => 'primary',
                                            'paid' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $milestoneStatusColors[$milestone->status] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                    </span>
                                    @if($milestone->status !== 'paid')
                                        <a href="{{ route('invoices.create', ['milestone_id' => $milestone->id]) }}" class="btn btn-outline btn-sm">
                                            Invoice
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i data-lucide="flag" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p>No milestones defined</p>
                </div>
            @endif
        </div>
        
        <!-- Invoices -->
        <div class="admin-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Invoices</h3>
            </div>
            
            @if($campaign->invoices->count() > 0)
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaign->invoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="font-medium hover:text-primary">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-gray-400">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td>{{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="table-action-btn">
                                            <i data-lucide="eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <p>No invoices created yet</p>
                    <a href="{{ route('invoices.create', ['campaign_id' => $campaign->id, 'brand_id' => $campaign->brand_id]) }}" class="btn btn-primary btn-sm mt-3">
                        <i data-lucide="file-plus"></i> Create Invoice
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="w-full lg:w-80 space-y-6">
        <div class="admin-card">
            <h3 class="card-title">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('invoices.create', ['campaign_id' => $campaign->id, 'brand_id' => $campaign->brand_id]) }}" class="btn btn-primary btn-block">
                    <i data-lucide="file-plus"></i> Create Invoice
                </a>
                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline btn-block">
                    <i data-lucide="edit"></i> Edit Campaign
                </a>
                @if($campaign->brand->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $campaign->brand->phone) }}" target="_blank" class="btn btn-outline btn-block">
                        <i data-lucide="message-circle"></i> WhatsApp Client
                    </a>
                @endif
            </div>
        </div>
        
        @if($campaign->notes)
            <div class="admin-card">
                <h3 class="card-title">Notes</h3>
                <p class="text-gray-400 whitespace-pre-wrap">{{ $campaign->notes }}</p>
            </div>
        @endif
        
        <div class="admin-card">
            <h3 class="card-title">Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-sm">Created</p>
                        <p class="text-xs text-gray-400">{{ $campaign->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @if($campaign->updated_at != $campaign->created_at)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-sm">Last Updated</p>
                            <p class="text-xs text-gray-400">{{ $campaign->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Milestone Modal -->
<div class="admin-modal-overlay" id="addMilestoneModal">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Add Milestone</h3>
            <button type="button" class="admin-modal-close" onclick="document.getElementById('addMilestoneModal').classList.remove('active')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('campaigns.milestones.store', $campaign) }}" method="POST">
            @csrf
            <div class="admin-modal-body">
                <div class="form-group">
                    <label class="form-label required">Title</label>
                    <input type="text" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Amount</label>
                        <input type="number" name="amount" class="form-input" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-input">
                    </div>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addMilestoneModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Milestone</button>
            </div>
        </form>
    </div>
</div>
@endsection