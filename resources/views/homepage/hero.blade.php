@extends('layouts.app')
@section('title', 'Hero / Banner Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-images me-2"></i>Hero / Banner Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Hero / Banner</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.hero.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-toggle-on me-2"></i>Visibility</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input type="hidden" name="enabled" value="0">
                        <input type="checkbox" name="enabled" class="form-check-input" value="1" {{ ($settings['enabled'] ?? '1') == '1' ? 'checked' : '' }} id="heroEnabled">
                        <label class="form-check-label" for="heroEnabled">Enable Hero / Banner Section</label>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-heading me-2"></i>Content</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Main Heading</label>
                        <input type="text" name="main_heading" class="form-control" value="{{ $settings['main_heading'] ?? '' }}" placeholder="What Starts Here Changes Everything">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Subheading</label>
                        <textarea name="subheading" class="form-control" rows="3" placeholder="Brief description under the heading">{{ $settings['subheading'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-bullhorn me-2"></i>Call To Action</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">CTA Button 1 Text</label>
                            <input type="text" name="cta_1_text" class="form-control" value="{{ $settings['cta_1_text'] ?? '' }}" placeholder="Explore Programs">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">CTA Button 1 URL</label>
                            <input type="text" name="cta_1_url" class="form-control" value="{{ $settings['cta_1_url'] ?? '' }}" placeholder="#">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">CTA Button 2 Text</label>
                            <input type="text" name="cta_2_text" class="form-control" value="{{ $settings['cta_2_text'] ?? '' }}" placeholder="Virtual Tour">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">CTA Button 2 URL</label>
                            <input type="text" name="cta_2_url" class="form-control" value="{{ $settings['cta_2_url'] ?? '' }}" placeholder="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-image me-2"></i>Background</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Background Image</label>
                        <input type="file" name="bg_image" class="form-control form-control-sm" accept="image/*">
                        @if($settings['bg_image'] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['bg_image']) }}" class="img-fluid rounded" style="max-height:60px"></div>@endif
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Background Video URL (MP4/YouTube)</label>
                        <input type="text" name="bg_video" class="form-control form-control-sm" value="{{ $settings['bg_video'] ?? '' }}" placeholder="URL or path">
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-magic me-2"></i>Animation</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Animation Style</label>
                        <select name="animation" class="form-select form-select-sm">
                            <option value="fade" {{ ($settings['animation'] ?? 'fade') == 'fade' ? 'selected' : '' }}>Fade</option>
                            <option value="slide" {{ ($settings['animation'] ?? '') == 'slide' ? 'selected' : '' }}>Slide</option>
                            <option value="zoom" {{ ($settings['animation'] ?? '') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                            <option value="none" {{ ($settings['animation'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Animation Duration (ms)</label>
                        <input type="number" name="animation_duration" class="form-control form-control-sm" value="{{ $settings['animation_duration'] ?? '750' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection