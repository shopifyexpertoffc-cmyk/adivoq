@extends('layouts.app')

@section('title', 'Creator Guides | CreatorPay')

@section('content')
<section class="section-padding relative">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-green-400 font-semibold text-sm uppercase tracking-wider">
                Creator Guides
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold mt-3 mb-2">
                Deep dives for <span class="gradient-text">serious creators</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Step-by-step guides on taxes, invoicing, brand deals, pricing and more.
            </p>
        </div>

        @if($posts->count())
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($posts as $post)
                    <article class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-green-500/50 transition-all duration-200 flex flex-col">
                        <p class="text-xs text-gray-400 mb-2">
                            {{ optional($post->published_at)->format('M d, Y') }}
                        </p>
                        <h2 class="text-lg font-semibold mb-2">
                            <a href="{{ route('guides.show', $post->slug) }}" class="hover:text-green-400">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if($post->excerpt)
                            <p class="text-gray-400 text-sm mb-4 flex-1">
                                {{ $post->excerpt }}
                            </p>
                        @endif
                        <a href="{{ route('guides.show', $post->slug) }}" class="text-sm text-green-400 hover:text-green-300 mt-auto">
                            Read guide →
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links('vendor.pagination.simple-tailwind') }}
            </div>
        @else
            <div class="text-center text-gray-400">
                Guides coming soon.
            </div>
        @endif
    </div>
</section>
@endsection