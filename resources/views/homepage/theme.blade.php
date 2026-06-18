@extends('layouts.app')
@section('title', 'Theme & Appearance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-paint-brush me-2"></i>Theme & Appearance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Theme</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.theme.update') }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-palette me-2"></i>Color Scheme</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Primary Color</label>
                        <div class="input-group">
                            <input type="color" name="primary_color" class="form-control form-control-color" value="{{ $settings['primary_color'] ?? '#BF5700' }}">
                            <input type="text" name="primary_color_hex" class="form-control" value="{{ $settings['primary_color'] ?? '#BF5700' }}" placeholder="#HEX">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Secondary Color</label>
                        <div class="input-group">
                            <input type="color" name="secondary_color" class="form-control form-control-color" value="{{ $settings['secondary_color'] ?? '#333F48' }}">
                            <input type="text" name="secondary_color_hex" class="form-control" value="{{ $settings['secondary_color'] ?? '#333F48' }}" placeholder="#HEX">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Accent Color</label>
                        <div class="input-group">
                            <input type="color" name="accent_color" class="form-control form-control-color" value="{{ $settings['accent_color'] ?? '#E87D1E' }}">
                            <input type="text" name="accent_color_hex" class="form-control" value="{{ $settings['accent_color'] ?? '#E87D1E' }}" placeholder="#HEX">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Background Color</label>
                        <div class="input-group">
                            <input type="color" name="bg_color" class="form-control form-control-color" value="{{ $settings['bg_color'] ?? '#ffffff' }}">
                            <input type="text" name="bg_color_hex" class="form-control" value="{{ $settings['bg_color'] ?? '#ffffff' }}" placeholder="#HEX">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-font me-2"></i>Typography</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Heading Font</label>
                        <select name="heading_font" class="form-select">
                            <option value="Playfair Display" {{ ($settings['heading_font'] ?? 'Playfair Display') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Serif)</option>
                            <option value="Poppins" {{ ($settings['heading_font'] ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins (Sans-Serif)</option>
                            <option value="Inter" {{ ($settings['heading_font'] ?? '') == 'Inter' ? 'selected' : '' }}>Inter (Sans-Serif)</option>
                            <option value="Merriweather" {{ ($settings['heading_font'] ?? '') == 'Merriweather' ? 'selected' : '' }}>Merriweather (Serif)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Body Font</label>
                        <select name="body_font" class="form-select">
                            <option value="Inter" {{ ($settings['body_font'] ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter (Sans-Serif)</option>
                            <option value="Poppins" {{ ($settings['body_font'] ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins (Sans-Serif)</option>
                            <option value="Open Sans" {{ ($settings['body_font'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans (Sans-Serif)</option>
                            <option value="Roboto" {{ ($settings['body_font'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto (Sans-Serif)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-mouse-pointer me-2"></i>Button Styles</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Button Shape</label>
                        <select name="button_shape" class="form-select">
                            <option value="rounded-0" {{ ($settings['button_shape'] ?? 'rounded-0') == 'rounded-0' ? 'selected' : '' }}>Square</option>
                            <option value="rounded-1" {{ ($settings['button_shape'] ?? '') == 'rounded-1' ? 'selected' : '' }}>Slightly Rounded</option>
                            <option value="rounded-pill" {{ ($settings['button_shape'] ?? '') == 'rounded-pill' ? 'selected' : '' }}>Pill / Fully Rounded</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Button Size</label>
                        <select name="button_size" class="form-select">
                            <option value="sm" {{ ($settings['button_size'] ?? '') == 'sm' ? 'selected' : '' }}>Small</option>
                            <option value="md" {{ ($settings['button_size'] ?? 'md') == 'md' ? 'selected' : '' }}>Medium</option>
                            <option value="lg" {{ ($settings['button_size'] ?? '') == 'lg' ? 'selected' : '' }}>Large</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-arrows-alt-v me-2"></i>Section Spacing</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Section Padding Top (px)</label>
                            <input type="number" name="section_padding_top" class="form-control" value="{{ $settings['section_padding_top'] ?? '80' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Section Padding Bottom (px)</label>
                            <input type="number" name="section_padding_bottom" class="form-control" value="{{ $settings['section_padding_bottom'] ?? '80' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Container Max Width (px)</label>
                            <input type="number" name="container_width" class="form-control" value="{{ $settings['container_width'] ?? '1200' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
<script>
document.querySelectorAll('input[type="color"]').forEach(function(picker) {
    var textInput = picker.closest('.input-group').querySelector('input[type="text"]');
    if (textInput) {
        picker.addEventListener('input', function() { textInput.value = this.value; });
        textInput.addEventListener('input', function() { picker.value = this.value; });
    }
});
</script>
@endsection