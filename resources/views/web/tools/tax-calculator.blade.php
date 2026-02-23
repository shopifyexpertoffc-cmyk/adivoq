@extends('layouts.app')

@section('title', 'Tax Calculator for Creators | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="text-green-400 font-semibold text-sm uppercase tracking-wider">
                Tools
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold mt-3 mb-2">
                Tax Calculator for <span class="gradient-text">Creators</span>
            </h1>
            <p class="text-gray-400">
                Rough estimate of how much tax you should keep aside from brand deals & freelance income.
            </p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-6">
            <form method="POST" action="{{ route('tax.calculate') }}" class="space-y-4">
                @csrf

                <div class="form-group">
                    <label class="form-label text-sm text-gray-300">Country</label>
                    <select name="country" class="form-input">
                        <option value="IN" {{ old('country') === 'IN' ? 'selected' : '' }}>India</option>
                        <option value="AE" {{ old('country') === 'AE' ? 'selected' : '' }}>UAE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label text-sm text-gray-300">Total Monthly Income (Brand deals + services)</label>
                    <input type="number" name="income" class="form-input" step="0.01" min="0" value="{{ old('income', '') }}" placeholder="e.g. 150000">
                </div>

                <div class="form-group">
                    <label class="form-label text-sm text-gray-300">Business Expenses (Software, editor, ads, etc.)</label>
                    <input type="number" name="expenses" class="form-input" step="0.01" min="0" value="{{ old('expenses', '') }}" placeholder="e.g. 30000">
                </div>

                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    Calculate
                </button>

                <p class="text-xs text-gray-500 mt-2">
                    This is a rough estimate, not professional advice. Always consult your CA/tax advisor.
                </p>
            </form>
        </div>

        @if(session('tax_result'))
            @php $r = session('tax_result'); @endphp
            <div class="bg-white/5 border border-green-500/40 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-4 text-green-400">Estimated Tax</h2>
                <div class="grid sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Taxable Income</p>
                        <p class="text-xl font-semibold">₹{{ number_format($r['taxable'], 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Tax Rate</p>
                        <p class="text-xl font-semibold">{{ $r['rate'] }}%</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Tax to Keep Aside</p>
                        <p class="text-xl font-semibold text-green-400">₹{{ number_format($r['tax'], 2) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection