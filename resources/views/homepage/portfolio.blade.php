@extends('layouts.app')
@section('title', 'Portfolio / Projects')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-briefcase me-2"></i>Portfolio / Projects</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Portfolio</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.portfolio.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-folder me-2"></i>Project Categories</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 8; $i++)
                    <div class="mb-1">
                        <input type="text" name="project_category_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['project_category_'.$i] ?? '' }}" placeholder="Category {{ $i }}">
                    </div>
                    @endfor
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-cog me-2"></i>Gallery Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Columns</label>
                        <select name="gallery_columns" class="form-select">
                            <option value="2" {{ ($settings['gallery_columns'] ?? '3') == '2' ? 'selected' : '' }}>2 Columns</option>
                            <option value="3" {{ ($settings['gallery_columns'] ?? '3') == '3' ? 'selected' : '' }}>3 Columns</option>
                            <option value="4" {{ ($settings['gallery_columns'] ?? '') == '4' ? 'selected' : '' }}>4 Columns</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="show_filter" value="0">
                        <input type="checkbox" name="show_filter" class="form-check-input" value="1" {{ ($settings['show_filter'] ?? '1') == '1' ? 'checked' : '' }} id="showFilter">
                        <label class="form-check-label" for="showFilter">Show Category Filter</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-star me-2"></i>Featured Projects</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <input type="text" name="featured_project_title_{{ $i }}" class="form-control form-control-sm mb-1" value="{{ $settings['featured_project_title_'.$i] ?? '' }}" placeholder="Project title">
                        <input type="file" name="featured_project_image_{{ $i }}" class="form-control form-control-sm" accept="image/*">
                        @if($settings['featured_project_image_'.$i] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['featured_project_image_'.$i]) }}" style="max-height:50px"></div>@endif
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection