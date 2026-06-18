@extends('layouts.app')
@section('title', 'Gallery Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-images me-2"></i>Gallery Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Gallery</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.gallery.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-images me-2"></i>Albums</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="mb-2 p-3 border rounded">
                        <div class="row g-2">
                            <div class="col-md-6"><input type="text" name="album_title_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['album_title_'.$i] ?? '' }}" placeholder="Album title"></div>
                            <div class="col-md-6"><input type="text" name="album_desc_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['album_desc_'.$i] ?? '' }}" placeholder="Short description"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-photo-video me-2"></i>Images</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 8; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2"><span class="badge bg-secondary">#{{ $i }}</span></div>
                            <div class="col-md-4"><input type="text" name="gallery_image_title_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['gallery_image_title_'.$i] ?? '' }}" placeholder="Image title"></div>
                            <div class="col-md-6"><input type="file" name="gallery_image_{{ $i }}" class="form-control form-control-sm" accept="image/*"></div>
                            @if($settings['gallery_image_'.$i] ?? false)
                            <div class="col-12 mt-1"><img src="{{ asset('storage/'.$settings['gallery_image_'.$i]) }}" style="max-height:50px"></div>
                            @endif
                        </div>
                    </div>
                    @endfor
                    <small class="text-muted">Up to 8 gallery images</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-th me-2"></i>Layout Options</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Layout Style</label>
                        <select name="layout" class="form-select">
                            <option value="grid" {{ ($settings['layout'] ?? 'grid') == 'grid' ? 'selected' : '' }}>Grid</option>
                            <option value="masonry" {{ ($settings['layout'] ?? '') == 'masonry' ? 'selected' : '' }}>Masonry</option>
                            <option value="slider" {{ ($settings['layout'] ?? '') == 'slider' ? 'selected' : '' }}>Slider</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Columns</label>
                        <select name="columns" class="form-select">
                            <option value="2" {{ ($settings['columns'] ?? '3') == '2' ? 'selected' : '' }}>2 Columns</option>
                            <option value="3" {{ ($settings['columns'] ?? '3') == '3' ? 'selected' : '' }}>3 Columns</option>
                            <option value="4" {{ ($settings['columns'] ?? '') == '4' ? 'selected' : '' }}>4 Columns</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="lightbox" value="0">
                        <input type="checkbox" name="lightbox" class="form-check-input" value="1" {{ ($settings['lightbox'] ?? '1') == '1' ? 'checked' : '' }} id="lightbox">
                        <label class="form-check-label" for="lightbox">Enable Lightbox</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection