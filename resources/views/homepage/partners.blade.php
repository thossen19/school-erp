@extends('layouts.app')
@section('title', 'Partners / Clients')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-handshake me-2"></i>Partners / Clients</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Partners</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.partners.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-images me-2"></i>Client Logos</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 12; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-1"><span class="badge bg-secondary">#{{ $i }}</span></div>
                            <div class="col-md-6"><input type="text" name="partner_name_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['partner_name_'.$i] ?? '' }}" placeholder="Partner name"></div>
                            <div class="col-md-5"><input type="file" name="partner_logo_{{ $i }}" class="form-control form-control-sm" accept="image/*"></div>
                            @if($settings['partner_logo_'.$i] ?? false)
                            <div class="col-12 mt-1"><img src="{{ asset('storage/'.$settings['partner_logo_'.$i]) }}" style="max-height:30px"></div>
                            @endif
                        </div>
                    </div>
                    @endfor
                    <small class="text-muted">Up to 12 partner logos</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-sliders-h me-2"></i>Logo Slider Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Autoplay Speed (ms)</label>
                        <input type="number" name="slider_speed" class="form-control" value="{{ $settings['slider_speed'] ?? '3000' }}">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="loop" value="0">
                        <input type="checkbox" name="loop" class="form-check-input" value="1" {{ ($settings['loop'] ?? '1') == '1' ? 'checked' : '' }} id="loop">
                        <label class="form-check-label" for="loop">Loop</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="grayscale" value="0">
                        <input type="checkbox" name="grayscale" class="form-check-input" value="1" {{ ($settings['grayscale'] ?? '0') == '1' ? 'checked' : '' }} id="grayscale">
                        <label class="form-check-label" for="grayscale">Grayscale Logos</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection