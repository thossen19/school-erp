@extends('layouts.app')
@section('title', 'Blog Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-blog me-2"></i>Blog Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Blog</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.blog.update') }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-newspaper me-2"></i>Blog Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Section Title</label>
                        <input type="text" name="section_title" class="form-control" value="{{ $settings['section_title'] ?? '' }}" placeholder="Latest News">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Number of Latest Posts to Show</label>
                        <input type="number" name="latest_posts_count" class="form-control" value="{{ $settings['latest_posts_count'] ?? '3' }}" min="1" max="12">
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-th-list me-2"></i>Categories</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="mb-1">
                        <input type="text" name="blog_category_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['blog_category_'.$i] ?? '' }}" placeholder="Category {{ $i }}">
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-star me-2"></i>Featured Posts</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <input type="text" name="featured_post_title_{{ $i }}" class="form-control form-control-sm mb-1" value="{{ $settings['featured_post_title_'.$i] ?? '' }}" placeholder="Post title">
                        <input type="text" name="featured_post_url_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['featured_post_url_'.$i] ?? '' }}" placeholder="URL or slug">
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection