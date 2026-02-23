@extends('layouts.admin')

@section('title', 'Plans')
@section('page-title', 'Plans Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-400">Manage subscription plans for your tenants.</p>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
        <i data-lucide="plus"></i> Add Plan
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($plans as $plan)
        <div class="admin-card {{ $plan->is_featured ? 'border-primary' : '' }}">
            @if($plan->is_featured)
                <span class="badge badge-primary mb-4">Most Popular</span>
            @endif
            
            <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
            <p class="text-gray-400 text-sm mb-4">{{ $plan->description }}</p>
            
            <div class="mb-6">
                <span class="text-3xl font-bold">
                    @if($plan->isFree())
                        Free
                    @else
                        ₹{{ number_format($plan->price_monthly) }}
                    @endif
                </span>
                @if(!$plan->isFree())
                    <span class="text-gray-400">/month</span>
                @endif
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center gap-2 text-sm">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    {{ $plan->hasUnlimitedInvoices() ? 'Unlimited' : $plan->max_invoices_per_month }} invoices/month
                </li>
                <li class="flex items-center gap-2 text-sm">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    Up to {{ $plan->max_team_members }} team members
                </li>
                <li class="flex items-center gap-2 text-sm">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    {{ $plan->trial_days }} days trial
                </li>
            </ul>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-outline btn-sm flex-1">
                    <i data-lucide="edit"></i> Edit
                </a>
                <form action="{{ route('admin.plans.toggleStatus', $plan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $plan->is_active ? 'btn-secondary' : 'btn-primary' }} btn-sm">
                        {{ $plan->is_active ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection