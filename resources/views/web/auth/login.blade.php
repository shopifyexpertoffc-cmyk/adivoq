@extends('layouts.app')

@section('title', 'Login | ' . (tenant('company_name') ?? tenant('name') ?? 'CreatorPay'))

@section('content')
<section class="relative min-h-[80vh] flex items-center">
    <div class="max-w-md mx-auto px-4 w-full">
        <div class="bg-black/50 border border-white/10 rounded-2xl p-6 shadow-2xl">
            <p class="text-xs text-green-400 uppercase tracking-wider mb-1">
                {{ tenant('company_name') ?? tenant('name') }}
            </p>
            <h1 class="text-2xl font-bold mb-1">
                Login to <span class="gradient-text">CreatorPay</span>
            </h1>
            <p class="text-gray-400 text-sm mb-5">Access your brands, campaigns, invoices & reports.</p>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/40 text-red-200 text-sm rounded-xl px-3 py-2 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.login', ['tenant' => tenant('id')]) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-green-400"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-green-400"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-xs text-gray-400">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="accent-green-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-black font-semibold py-2 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-colors">
                    Login
                </button>
            </form>
        </div>
    </div>
</section>
@endsection