@extends('layouts.app')

@section('title', $post->title . ' | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-xs text-green-400 uppercase tracking-wider mb-2">
            {{ $post->type === 'guide' ? 'Creator Guide' : 'Blog' }}
        </p>
        <h1 class="text-3xl sm:text-4xl font-bold mb-2">
            {{ $post->title }}
        </h1>
        <p class="text-xs text-gray-400 mb-6">
            {{ optional($post->published_at)->format('M d, Y') }}
            @if($post->category)
                • {{ $post->category }}
            @endif
        </p>

        <div class="prose prose-invert max-w-none text-gray-100 text-base leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
</section>
@endsection