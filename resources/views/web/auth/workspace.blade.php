@extends('layouts.app')

@section('title', 'Your Workspace | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-black/50 border border-white/10 rounded-2xl p-6 shadow-2xl">
            <h1 class="text-2xl font-bold mb-1">
                Enter your <span class="gradient-text">workspace</span>
            </h1>
            <p class="text-gray-400 text-sm mb-5">
                Example: if your workspace is "sani", we’ll redirect you to sani.adivoq.com/login
            </p>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/40 text-red-200 text-sm rounded-xl px-3 py-2 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('central.tenant.redirect') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Workspace</label>
                    <input type="text" name="workspace"
                           value="{{ old('workspace') }}"
                           class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-green-400"
                           placeholder="e.g. sani" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    Continue
                </button>
            </form>
        </div>
    </div>
</section>
@endsection