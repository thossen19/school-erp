@extends('layouts.app')
@section('title', 'Footer Homepage Widgets')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-shoe-prints me-2"></i>Footer Homepage Widgets</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Footer Widgets</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.footer-widgets.update') }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-align-left me-2"></i>Footer Content</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Footer Description / About Text</label>
                        <textarea name="footer_description" class="form-control" rows="4" placeholder="Brief description of school">{{ $settings['footer_description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Footer Logo</label>
                        <input type="file" name="footer_logo" class="form-control form-control-sm" accept="image/*">
                        @if($settings['footer_logo'] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['footer_logo']) }}" style="max-height:40px"></div>@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-link me-2"></i>Quick Links</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 8; $i++)
                    <div class="row g-1 mb-1">
                        <div class="col-7"><input type="text" name="quick_link_label_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['quick_link_label_'.$i] ?? '' }}" placeholder="Link label"></div>
                        <div class="col-5"><input type="text" name="quick_link_url_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['quick_link_url_'.$i] ?? '' }}" placeholder="URL"></div>
                    </div>
                    @endfor
                    <small class="text-muted">Up to 8 quick links</small>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-copyright me-2"></i>Copyright Text</h6></div>
                <div class="card-body">
                    <input type="text" name="copyright_text" class="form-control" value="{{ $settings['copyright_text'] ?? '' }}" placeholder="© 2026 School Name. All rights reserved.">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection