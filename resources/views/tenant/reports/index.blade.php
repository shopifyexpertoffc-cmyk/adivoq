@extends('layouts.tenant')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<!-- Year Filter -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <select id="yearFilter" class="form-select" style="width: auto;" onchange="window.location.href='{{ route('reports.index') }}?year=' + this.value">
            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('reports.export', ['type' => 'payments', 'from_date' => $year . '-01-01', 'to_date' => $year . '-12-31']) }}" class="btn btn-outline">
            <i data-lucide="download"></i> Export Payments
        </a>
        <a href="{{ route('reports.export', ['type' => 'invoices', 'from_date' => $year . '-01-01', 'to_date' => $year . '-12-31']) }}" class="btn btn-outline">
            <i data-lucide="download"></i> Export Invoices
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid mb-6">
    <div class="stat-card">
        <div class="stat-card-icon green">
            <i data-lucide="indian-rupee"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_revenue']) }}</div>
        <div class="stat-card-label">Total Revenue ({{ $year }})</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue">
            <i data-lucide="file-text"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['total_invoiced']) }}</div>
        <div class="stat-card-label">Total Invoiced</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">
            <i data-lucide="receipt"></i>
        </div>
        <div class="stat-card-value">₹{{ number_format($stats['avg_invoice']) }}</div>
        <div class="stat-card-label">Avg Invoice Value</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">
            <i data-lucide="briefcase"></i>
        </div>
        <div class="stat-card-value">{{ $stats['completed_campaigns'] }}/{{ $stats['total_campaigns'] }}</div>
        <div class="stat-card-label">Campaigns Completed</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Monthly Revenue Chart -->
    <div class="admin-card lg:col-span-2">
        <h3 class="card-title">Monthly Revenue ({{ $year }})</h3>
        <div class="h-64 flex items-end justify-between gap-2">
            @php
                $maxRevenue = max($revenueData) ?: 1;
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            @endphp
            @foreach($revenueData as $index => $revenue)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-primary/80 rounded-t-lg transition-all hover:bg-primary" 
                         style="height: {{ ($revenue / $maxRevenue) * 200 }}px; min-height: 4px;"
                         title="₹{{ number_format($revenue) }}">
                    </div>
                    <span class="text-xs text-gray-400 mt-2">{{ $months[$index] }}</span>
                    <span class="text-xs text-gray-500">₹{{ number_format($revenue / 1000, 0) }}k</span>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top Brands -->
    <div class="admin-card">
        <h3 class="card-title">Top Brands by Revenue</h3>
        @if($topBrands->count() > 0)
            <div class="space-y-4">
                @foreach($topBrands as $brand)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-lg flex items-center justify-center font-bold">
                                {{ strtoupper(substr($brand->name, 0, 1)) }}
                            </div>
                            <a href="{{ route('brands.show', $brand) }}" class="font-medium hover:text-primary">
                                {{ $brand->name }}
                            </a>
                        </div>
                        <span class="font-semibold">₹{{ number_format($brand->invoices_sum_total_amount ?? 0) }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No data available</p>
        @endif
    </div>
    
    <!-- Revenue by Platform -->
    <div class="admin-card">
        <h3 class="card-title">Revenue by Platform</h3>
        @if($platformRevenue->count() > 0)
            <div class="space-y-4">
                @php
                    $totalPlatformRevenue = $platformRevenue->sum('total') ?: 1;
                @endphp
                @foreach($platformRevenue as $platform)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span>{{ $platform->platform }}</span>
                            <span class="font-semibold">₹{{ number_format($platform->total) }}</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ ($platform->total / $totalPlatformRevenue) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No data available</p>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <a href="{{ route('reports.revenue') }}" class="admin-card hover:border-primary/30 transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="trending-up" class="w-6 h-6 text-green-400"></i>
            </div>
            <div>
                <h3 class="font-semibold">Revenue Report</h3>
                <p class="text-sm text-gray-400">Detailed payment breakdown</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('reports.tax') }}" class="admin-card hover:border-primary/30 transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="calculator" class="w-6 h-6 text-blue-400"></i>
            </div>
            <div>
                <h3 class="font-semibold">Tax Report</h3>
                <p class="text-sm text-gray-400">GST/TDS summary</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('reports.clients') }}" class="admin-card hover:border-primary/30 transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-purple-400"></i>
            </div>
            <div>
                <h3 class="font-semibold">Client Report</h3>
                <p class="text-sm text-gray-400">Client-wise analytics</p>
            </div>
        </div>
    </a>
</div>
@endsection