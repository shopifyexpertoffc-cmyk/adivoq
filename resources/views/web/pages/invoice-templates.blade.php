@extends('layouts.app')

@section('title', 'Invoice Templates for Creators | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-green-400 font-semibold text-sm uppercase tracking-wider">
                Resources
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold mt-3 mb-2">
                Invoice Templates for <span class="gradient-text">Creators</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Download ready-to-use invoice formats or generate a free invoice online without logging in.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-2">Google Sheets / Excel Template</h2>
                <p class="text-gray-400 text-sm mb-4">
                    A simple invoice template for creators who prefer Sheets/Excel.
                </p>
                <a href="#" class="btn btn-secondary btn-sm">Download Template</a>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-2">PDF Printable Template</h2>
                <p class="text-gray-400 text-sm mb-4">
                    Clean PDF layout for printing or sending manually.
                </p>
                <a href="#" class="btn btn-secondary btn-sm">Download PDF</a>
            </div>
        </div>

        <div class="mt-10 bg-white/5 border border-green-500/40 rounded-2xl p-6 text-center">
            <h2 class="text-xl font-semibold mb-2">Create a Free Invoice Online</h2>
            <p class="text-gray-400 text-sm mb-4">
                No signup required. Create an invoice in under 60 seconds.
            </p>
            <a href="{{ route('invoice.free') }}" class="btn btn-primary">
                Create Free Invoice
            </a>
        </div>
    </div>
</section>
@endsection