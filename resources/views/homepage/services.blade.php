@extends('layouts.app')
@section('title', 'Services Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-concierge-bell me-2"></i>Services Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Services</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.services.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-heading me-2"></i>Section Title</h6></div>
        <div class="card-body">
            <input type="text" name="section_title" class="form-control" value="{{ $settings['section_title'] ?? '' }}" placeholder="Our Services">
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-th-list me-2"></i>Service Cards</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 6; $i++)
            <div class="mb-2 p-3 border rounded service-card">
                <div class="row g-2 align-items-center">
                    <div class="col-md-1"><span class="badge bg-secondary">#{{ $i }}</span></div>
                    <div class="col-md-3"><input type="text" name="service_title_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['service_title_'.$i] ?? '' }}" placeholder="Service name"></div>
                    <div class="col-md-2"><input type="text" name="service_icon_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['service_icon_'.$i] ?? '' }}" placeholder="Icon class (fa-*)"></div>
                    <div class="col-md-2"><input type="number" name="service_order_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['service_order_'.$i] ?? $i }}" placeholder="Order"></div>
                    <div class="col-md-4"><input type="text" name="service_desc_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['service_desc_'.$i] ?? '' }}" placeholder="Short description"></div>
                </div>
            </div>
            @endfor
            <small class="text-muted">Up to 6 services. Icons use Font Awesome class names (e.g. fa-book-open, fa-flask).</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection