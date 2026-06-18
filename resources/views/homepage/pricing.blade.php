@extends('layouts.app')
@section('title', 'Pricing Plans')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Pricing Plans</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Pricing</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.pricing.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-dollar-sign me-2"></i>Plan Management</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 4; $i++)
            <div class="mb-3 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-2"><span class="badge bg-secondary fs-6">Plan {{ $i }}</span></div>
                    <div class="col-md-3"><input type="text" name="plan_name_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['plan_name_'.$i] ?? '' }}" placeholder="Plan name"></div>
                    <div class="col-md-2"><input type="text" name="plan_price_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['plan_price_'.$i] ?? '' }}" placeholder="Price (e.g. $19/mo)"></div>
                    <div class="col-md-2">
                        <select name="plan_featured_{{ $i }}" class="form-select form-select-sm">
                            <option value="0" {{ ($settings['plan_featured_'.$i] ?? '0') == '0' ? 'selected' : '' }}>Standard</option>
                            <option value="1" {{ ($settings['plan_featured_'.$i] ?? '') == '1' ? 'selected' : '' }}>Featured</option>
                        </select>
                    </div>
                    <div class="col-md-3"><input type="text" name="plan_btn_text_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['plan_btn_text_'.$i] ?? '' }}" placeholder="Button text"></div>
                </div>
                <div class="mt-2">
                    <label class="small fw-semibold">Features (one per line)</label>
                    <textarea name="plan_features_{{ $i }}" class="form-control form-control-sm" rows="3" placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ $settings['plan_features_'.$i] ?? '' }}</textarea>
                </div>
            </div>
            @endfor
            <small class="text-muted">Up to 4 pricing plans</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection