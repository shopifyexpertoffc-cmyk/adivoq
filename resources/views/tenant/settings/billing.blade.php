@extends('layouts.tenant')

@section('title', 'Billing Settings')
@section('page-title', 'Billing & Subscription')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card">
        <h2 class="card-title">Current Subscription</h2>

        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Plan</span>
                <span class="info-value badge badge-primary">
                    {{ ucfirst(tenant('plan') ?? 'free') }}
                </span>
            </div>

            @if(tenant('trial_ends_at'))
                <div class="info-item">
                    <span class="info-label">Trial Ends</span>
                    <span class="info-value">
                        {{ tenant('trial_ends_at')->format('M d, Y') }}
                    </span>
                </div>
            @endif
        </div>

        <p class="text-sm text-gray-400 mt-4">
            Online billing & automatic plan upgrades will be added soon.
            For now, please contact support at <strong>hello@adivoq.com</strong> to change your plan.
        </p>
    </div>
</div>
@endsection