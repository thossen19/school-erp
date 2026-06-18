@extends('layouts.app')
@section('title', 'Products Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-box me-2"></i>Products Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Products</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.products.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-th-large me-2"></i>Display Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Display Style</label>
                        <select name="display_style" class="form-select">
                            <option value="slider" {{ ($settings['display_style'] ?? 'slider') == 'slider' ? 'selected' : '' }}>Slider</option>
                            <option value="grid" {{ ($settings['display_style'] ?? '') == 'grid' ? 'selected' : '' }}>Grid</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-tags me-2"></i>Product Categories</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="mb-2">
                        <input type="text" name="category_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['category_'.$i] ?? '' }}" placeholder="Category {{ $i }}">
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-star me-2"></i>Featured Products</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <input type="text" name="featured_title_{{ $i }}" class="form-control form-control-sm mb-1" value="{{ $settings['featured_title_'.$i] ?? '' }}" placeholder="Product title">
                        <input type="file" name="featured_image_{{ $i }}" class="form-control form-control-sm" accept="image/*">
                        @if($settings['featured_image_'.$i] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['featured_image_'.$i]) }}" style="max-height:50px"></div>@endif
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection