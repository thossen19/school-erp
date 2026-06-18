@extends('layouts.app')
@section('title', 'Call To Action (CTA)')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bullhorn me-2"></i>Call To Action</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">CTA</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.cta.update') }}">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-bullhorn me-2"></i>Call To Action Content</h6></div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12">
                    <label class="form-label fw-semibold small">Heading</label>
                    <input type="text" name="heading" class="form-control" value="{{ $settings['heading'] ?? '' }}" placeholder="For Our Community, For the Future">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Supporting text under heading">{{ $settings['description'] ?? '' }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Button Text</label>
                    <input type="text" name="button_text" class="form-control" value="{{ $settings['button_text'] ?? '' }}" placeholder="Get Started">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Button URL</label>
                    <input type="text" name="button_url" class="form-control" value="{{ $settings['button_url'] ?? '' }}" placeholder="#">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection