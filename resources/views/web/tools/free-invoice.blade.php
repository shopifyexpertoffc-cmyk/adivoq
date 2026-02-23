@extends('layouts.app')

@section('title', 'Create Free Invoice Online | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="text-green-400 font-semibold text-sm uppercase tracking-wider">
                Free Tool
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold mt-3 mb-2">
                Create a Free <span class="gradient-text">Invoice</span>
            </h1>
            <p class="text-gray-400">
                Fill a few fields and download a clean invoice PDF (or print to PDF in your browser).
            </p>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
            <form action="{{ route('invoice.free.generate') }}" method="POST" class="space-y-4">
                @csrf

                <h2 class="text-lg font-semibold mb-3">Your Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Your Name / Brand Name *</label>
                        <input type="text" name="your_name" class="form-input" value="{{ old('your_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Your Address</label>
                        <textarea name="your_address" class="form-textarea" rows="2">{{ old('your_address') }}</textarea>
                    </div>
                </div>

                <h2 class="text-lg font-semibold mb-3 mt-4">Client Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="client_name" class="form-input" value="{{ old('client_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Client Email</label>
                        <input type="email" name="client_email" class="form-input" value="{{ old('client_email') }}">
                    </div>
                </div>

                <h2 class="text-lg font-semibold mb-2 mt-4">Invoice Items</h2>
                <p class="text-xs text-gray-400 mb-3">Add up to 5 line items.</p>

                @php
                    $oldItems = old('items', [
                        ['desc' => 'Service description', 'qty' => 1, 'rate' => 0],
                    ]);
                @endphp

                <div class="space-y-3">
                    @foreach($oldItems as $i => $item)
                        <div class="grid md:grid-cols-4 gap-2">
                            <div class="md:col-span-2">
                                <input type="text" name="items[{{ $i }}][desc]" class="form-input"
                                       value="{{ $item['desc'] ?? '' }}" placeholder="Description" required>
                            </div>
                            <div>
                                <input type="number" name="items[{{ $i }}][qty]" class="form-input"
                                       value="{{ $item['qty'] ?? 1 }}" min="1" step="1" required>
                            </div>
                            <div>
                                <input type="number" name="items[{{ $i }}][rate]" class="form-input"
                                       value="{{ $item['rate'] ?? 0 }}" min="0" step="0.01" required>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary mt-6">
                    Generate Invoice Preview
                </button>
            </form>
        </div>
    </div>
</section>
@endsection