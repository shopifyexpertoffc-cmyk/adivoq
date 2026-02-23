<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:blog_posts,slug',
            'type'      => 'required|in:blog,guide',
            'category'  => 'nullable|string|max:100',
            'excerpt'   => 'nullable|string|max:300',
            'content'   => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'status'    => 'required|in:draft,published',
        ]);

        if (!$data['slug']) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blog', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $data['author_id'] = auth('admin')->id();

        BlogPost::create($data);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Post created.');
    }

    public function edit(BlogPost $blog_post)
    {
        return view('admin.blog.edit', ['post' => $blog_post]);
    }

    public function update(Request $request, BlogPost $blog_post)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog_post->id,
            'type'      => 'required|in:blog,guide',
            'category'  => 'nullable|string|max:100',
            'excerpt'   => 'nullable|string|max:300',
            'content'   => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'status'    => 'required|in:draft,published',
        ]);

        if (!$data['slug']) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blog', 'public');
        }

        if ($data['status'] === 'published' && !$blog_post->published_at) {
            $data['published_at'] = now();
        }

        $blog_post->update($data);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $blog_post)
    {
        $blog_post->delete();

        return back()->with('success', 'Post deleted.');
    }
}