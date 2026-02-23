@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page-title', 'Blog & Guides')

@section('content')
<div class="admin-card mb-4">
    <div class="flex justify-between items-center">
        <h2 class="card-title">Posts</h2>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus"></i> New Post
        </a>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ ucfirst($post->type) }}</td>
                        <td>
                            <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-secondary' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td>{{ optional($post->published_at)->format('M d, Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.blog-posts.edit', $post) }}" class="table-action-btn">
                                    <i data-lucide="edit"></i>
                                </a>
                                <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="table-action-btn danger">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-6">No posts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>
</div>
@endsection