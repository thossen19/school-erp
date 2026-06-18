@extends('layouts.app')
@section('title', 'Navigation Settings')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bars me-2"></i>Navigation Settings</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Navigation</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.navigation.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-thumbtack me-2"></i>Header Settings</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="sticky_header" value="0">
                        <input type="checkbox" name="sticky_header" class="form-check-input" value="1" {{ ($settings['sticky_header'] ?? '1') == '1' ? 'checked' : '' }} id="stickyHeader">
                        <label class="form-check-label" for="stickyHeader">Sticky Header (fixed on scroll)</label>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-image me-2"></i>Logo</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Logo Image</label>
                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                        @if($settings['logo'] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['logo']) }}" class="img-fluid rounded" style="max-height:50px"></div>@endif
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Logo Text (fallback if no image)</label>
                        <input type="text" name="logo_text" class="form-control" value="{{ $settings['logo_text'] ?? '' }}" placeholder="School Name">
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-layer-group me-2"></i>Menu Layout</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Layout Style</label>
                        <select name="menu_layout" class="form-select">
                            <option value="classic" {{ ($settings['menu_layout'] ?? 'classic') == 'classic' ? 'selected' : '' }}>Classic (Left Logo + Center Menu)</option>
                            <option value="centered" {{ ($settings['menu_layout'] ?? '') == 'centered' ? 'selected' : '' }}>Centered (Logo + Menu stacked)</option>
                            <option value="modern" {{ ($settings['menu_layout'] ?? '') == 'modern' ? 'selected' : '' }}>Modern (Hamburger + Fullscreen)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-cog me-2"></i>Top Bar Settings</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="top_bar_enabled" value="0">
                        <input type="checkbox" name="top_bar_enabled" class="form-check-input" value="1" {{ ($settings['top_bar_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="topBar">
                        <label class="form-check-label" for="topBar">Enable Top Bar</label>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Top Bar Background Color</label>
                        <input type="color" name="top_bar_bg" class="form-control form-control-color" value="{{ $settings['top_bar_bg'] ?? '#333F48' }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Top Bar Text Color</label>
                        <input type="color" name="top_bar_text_color" class="form-control form-control-color" value="{{ $settings['top_bar_text_color'] ?? '#ffffff' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection