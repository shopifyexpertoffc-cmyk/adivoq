<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->where('type', 'blog')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('web.blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('web.blog.show', compact('post'));
    }

    public function guides()
    {
        $posts = BlogPost::published()
            ->where('type', 'guide')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('web.guides.index', compact('posts'));
    }

    public function guideShow(string $slug)
    {
        $post = BlogPost::published()
            ->where('type', 'guide')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('web.guides.show', compact('post'));
    }
}