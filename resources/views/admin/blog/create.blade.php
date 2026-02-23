@extends('layouts.admin')

@section('title', 'Create Post')
@section('page-title', 'Create Blog Post')

@section('content')
<div class="max-w-3xl">
    <div class="admin-card">
        <h2 class="card-title">New Post</h2>

        <form action="{{ route('admin.blog-posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-input" value="{{ old('title') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select" required>
                        <option value="blog" {{ old('type') === 'blog' ? 'selected' : '' }}>Blog</option>
                        <option value="guide" {{ old('type') === 'guide' ? 'selected' : '' }}>Creator Guide</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-input" value="{{ old('category') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Excerpt</label>
                <textarea name="excerpt" class="form-textarea" rows="2">{{ old('excerpt') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Content *</label>
                <textarea name="content" class="form-textarea" rows="8" required>{{ old('content') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div class="form-actions mt-4">
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Publish</button>
            </div>
        </form>
    </div>
</div>
@endsection