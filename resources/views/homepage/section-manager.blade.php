@extends('layouts.app')
@section('title', 'Section Manager')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-layer-group me-2"></i>Section Manager</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Section Manager</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.section-manager.update') }}">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-arrows-alt me-2"></i>Drag & Drop Section Ordering</h6></div>
        <div class="card-body">
            <p class="small text-muted mb-3">Arrange the order of homepage sections. Use the order numbers to set positions (lower = higher).</p>
            @php
                $sections = [
                    'hero' => 'Hero / Banner',
                    'about' => 'About Section',
                    'services' => 'Services Section',
                    'features' => 'Features Section',
                    'products' => 'Products Section',
                    'portfolio' => 'Portfolio / Projects',
                    'testimonials' => 'Testimonials',
                    'team' => 'Team Section',
                    'statistics' => 'Statistics / Counters',
                    'video' => 'Video Section',
                    'faq' => 'FAQ Section',
                    'pricing' => 'Pricing Plans',
                    'blog' => 'Blog Section',
                    'cta' => 'Call To Action',
                    'newsletter' => 'Newsletter',
                    'partners' => 'Partners / Clients',
                    'gallery' => 'Gallery Section',
                    'contact' => 'Contact Section',
                ];
            @endphp
            <div class="row g-2">
                @foreach($sections as $key => $label)
                @php $stored = $settings[$key.'_order'] ?? $loop->iteration; @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="p-2 border rounded d-flex align-items-center gap-2 section-sort-item" data-section="{{ $key }}">
                        <span class="text-muted" style="cursor:grab"><i class="fas fa-grip-vertical"></i></span>
                        <span class="flex-grow-1 small">{{ $label }}</span>
                        <input type="number" name="{{ $key }}_order" class="form-control form-control-sm" style="width:60px" value="{{ is_numeric($stored) ? $stored : $loop->iteration }}" min="1" max="99">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-eye me-2"></i>Enable / Disable Sections</h6></div>
                <div class="card-body">
                    @foreach($sections as $key => $label)
                    <div class="form-check form-switch mb-1">
                        <input type="hidden" name="{{ $key }}_enabled" value="0">
                        <input type="checkbox" name="{{ $key }}_enabled" class="form-check-input" value="1" {{ ($settings[$key.'_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="{{ $key }}Enabled">
                        <label class="form-check-label small" for="{{ $key }}Enabled">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-device me-2"></i>Visibility by Device</h6></div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Show/hide sections on specific devices.</p>
                    @foreach($sections as $key => $label)
                    <div class="mb-2 p-2 border rounded">
                        <div class="small fw-semibold mb-1">{{ $label }}</div>
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="{{ $key }}_visible_desktop" value="0">
                                <input class="form-check-input" type="checkbox" name="{{ $key }}_visible_desktop" value="1" {{ ($settings[$key.'_visible_desktop'] ?? '1') == '1' ? 'checked' : '' }} id="{{ $key }}Desktop">
                                <label class="form-check-label small" for="{{ $key }}Desktop"><i class="fas fa-desktop"></i> Desktop</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="{{ $key }}_visible_tablet" value="0">
                                <input class="form-check-input" type="checkbox" name="{{ $key }}_visible_tablet" value="1" {{ ($settings[$key.'_visible_tablet'] ?? '1') == '1' ? 'checked' : '' }} id="{{ $key }}Tablet">
                                <label class="form-check-label small" for="{{ $key }}Tablet"><i class="fas fa-tablet-alt"></i> Tablet</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="{{ $key }}_visible_mobile" value="0">
                                <input class="form-check-input" type="checkbox" name="{{ $key }}_visible_mobile" value="1" {{ ($settings[$key.'_visible_mobile'] ?? '1') == '1' ? 'checked' : '' }} id="{{ $key }}Mobile">
                                <label class="form-check-label small" for="{{ $key }}Mobile"><i class="fas fa-mobile-alt"></i> Mobile</label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection