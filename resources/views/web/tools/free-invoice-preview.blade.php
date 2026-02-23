@extends('layouts.app')

@section('title', 'Invoice Preview | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-4xl mx-auto px-4 bg-white/5 border border-white/10 rounded-2xl p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold">Invoice</h1>
                <p class="text-sm text-gray-400">{{ $data['your_name'] }}</p>
                @if(!empty($data['your_address']))
                    <p class="text-xs text-gray-400 whitespace-pre-line">{{ $data['your_address'] }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 uppercase">Bill To</p>
                <p class="font-semibold">{{ $data['client_name'] }}</p>
                @if(!empty($data['client_email']))
                    <p class="text-xs text-gray-400">{{ $data['client_email'] }}</p>
                @endif
            </div>
        </div>

        <table class="w-full text-sm mb-6">
            <thead>
                <tr class="border-b border-white/10 text-gray-400">
                    <th class="text-left pb-2">Description</th>
                    <th class="text-right pb-2">Qty</th>
                    <th class="text-right pb-2">Rate</th>
                    <th class="text-right pb-2">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr class="border-b border-white/5">
                        <td class="py-2">{{ $item['desc'] }}</td>
                        <td class="py-2 text-right">{{ $item['qty'] }}</td>
                        <td class="py-2 text-right">₹{{ number_format($item['rate'], 2) }}</td>
                        <td class="py-2 text-right">₹{{ number_format($item['amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-400">Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-semibold">
                    <span>Total</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500">
            Tip: Use your browser’s “Print” → “Save as PDF” to download this invoice.
        </p>
    </div>
</section>
@endsection