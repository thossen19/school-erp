@extends('layouts.app')
@section('title', 'SEO Settings')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-search me-2"></i>SEO Settings</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">SEO</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.seo.update') }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-tag me-2"></i>Meta Tags</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? '' }}" placeholder="School Name - Excellence in Education">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description for search engines">{{ $settings['meta_description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Meta Keywords (comma-separated)</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="school, education, learning">
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-code me-2"></i>Structured Data (Schema)</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Schema Type</label>
                        <select name="schema_type" class="form-select">
                            <option value="EducationalOrganization" {{ ($settings['schema_type'] ?? 'EducationalOrganization') == 'EducationalOrganization' ? 'selected' : '' }}>Educational Organization</option>
                            <option value="School" {{ ($settings['schema_type'] ?? '') == 'School' ? 'selected' : '' }}>School</option>
                            <option value="Organization" {{ ($settings['schema_type'] ?? '') == 'Organization' ? 'selected' : '' }}>Organization</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Custom Schema JSON-LD</label>
                        <textarea name="custom_schema" class="form-control" rows="5" placeholder="{ '@context': 'https://schema.org', ... }">{{ $settings['custom_schema'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-link me-2"></i>Canonical URL</h6></div>
                <div class="card-body">
                    <input type="text" name="canonical_url" class="form-control" value="{{ $settings['canonical_url'] ?? '' }}" placeholder="https://school.edu/">
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-robot me-2"></i>Robots Meta Tags</h6></div>
                <div class="card-body">
                    <div class="form-check mb-1">
                        <input type="hidden" name="robots_noindex" value="0">
                        <input class="form-check-input" type="checkbox" name="robots_noindex" value="1" {{ ($settings['robots_noindex'] ?? '0') == '1' ? 'checked' : '' }} id="noindex">
                        <label class="form-check-label small" for="noindex">No Index</label>
                    </div>
                    <div class="form-check mb-1">
                        <input type="hidden" name="robots_nofollow" value="0">
                        <input class="form-check-input" type="checkbox" name="robots_nofollow" value="1" {{ ($settings['robots_nofollow'] ?? '0') == '1' ? 'checked' : '' }} id="nofollow">
                        <label class="form-check-label small" for="nofollow">No Follow</label>
                    </div>
                    <div class="form-check mb-1">
                        <input type="hidden" name="robots_noarchive" value="0">
                        <input class="form-check-input" type="checkbox" name="robots_noarchive" value="1" {{ ($settings['robots_noarchive'] ?? '0') == '1' ? 'checked' : '' }} id="noarchive">
                        <label class="form-check-label small" for="noarchive">No Archive</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection