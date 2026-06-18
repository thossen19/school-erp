@extends('layouts.app')
@section('title', 'Features Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-star me-2"></i>Features Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Features</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.features.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Feature List</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 8; $i++)
            <div class="mb-2 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-1"><span class="badge bg-secondary">#{{ $i }}</span></div>
                    <div class="col-md-3"><input type="text" name="feature_title_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['feature_title_'.$i] ?? '' }}" placeholder="Feature title"></div>
                    <div class="col-md-2"><input type="text" name="feature_icon_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['feature_icon_'.$i] ?? '' }}" placeholder="Icon class (fa-*)"></div>
                    <div class="col-md-6"><input type="text" name="feature_desc_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['feature_desc_'.$i] ?? '' }}" placeholder="Description"></div>
                </div>
                @if($i <= 4)
                <div class="mt-1">
                    <label class="small text-muted">Feature Image (optional)</label>
                    <input type="file" name="feature_image_{{ $i }}" class="form-control form-control-sm" accept="image/*">
                    @if($settings['feature_image_'.$i] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['feature_image_'.$i]) }}" style="max-height:40px"></div>@endif
                </div>
                @endif
            </div>
            @endfor
            <small class="text-muted">Up to 8 features. First 4 can have images.</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection