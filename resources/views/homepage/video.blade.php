@extends('layouts.app')
@section('title', 'Video Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-video me-2"></i>Video Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Video</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.video.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-link me-2"></i>Video URL</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">YouTube / Vimeo URL</label>
                        <input type="text" name="video_url" class="form-control" value="{{ $settings['video_url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Video Title</label>
                        <input type="text" name="video_title" class="form-control" value="{{ $settings['video_title'] ?? '' }}" placeholder="Optional title">
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-play-circle me-2"></i>Popup Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Popup Behavior</label>
                        <select name="popup_type" class="form-select">
                            <option value="modal" {{ ($settings['popup_type'] ?? 'modal') == 'modal' ? 'selected' : '' }}>Modal / Lightbox</option>
                            <option value="inline" {{ ($settings['popup_type'] ?? '') == 'inline' ? 'selected' : '' }}>Inline (autoplay)</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="autoplay" value="0">
                        <input type="checkbox" name="autoplay" class="form-check-input" value="1" {{ ($settings['autoplay'] ?? '1') == '1' ? 'checked' : '' }} id="autoplay">
                        <label class="form-check-label" for="autoplay">Autoplay</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-image me-2"></i>Thumbnail</h6></div>
                <div class="card-body text-center">
                    <input type="file" name="thumbnail" class="form-control form-control-sm mb-2" accept="image/*">
                    @if($settings['thumbnail'] ?? false)
                    <img src="{{ asset('storage/'.$settings['thumbnail']) }}" class="img-fluid rounded" style="max-height:120px">
                    @else
                    <div class="text-muted py-4"><i class="fas fa-image fa-3x"></i><p class="small mt-1">No thumbnail</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection