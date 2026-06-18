@extends('layouts.app')
@section('title', 'Newsletter Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-envelope-open-text me-2"></i>Newsletter Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Newsletter</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.newsletter.update') }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-toggle-on me-2"></i>Settings</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="hidden" name="enabled" value="0">
                        <input type="checkbox" name="enabled" class="form-check-input" value="1" {{ ($settings['enabled'] ?? '1') == '1' ? 'checked' : '' }} id="nlEnabled">
                        <label class="form-check-label" for="nlEnabled">Enable Newsletter Section</label>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Section Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $settings['title'] ?? '' }}" placeholder="Stay Updated">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Subscription Form Text</label>
                        <textarea name="form_text" class="form-control" rows="2" placeholder="Subscribe to our newsletter">{{ $settings['form_text'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-plug me-2"></i>Integration Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Service Provider</label>
                        <select name="service" class="form-select form-select-sm">
                            <option value="mailchimp" {{ ($settings['service'] ?? 'mailchimp') == 'mailchimp' ? 'selected' : '' }}>Mailchimp</option>
                            <option value="sendgrid" {{ ($settings['service'] ?? '') == 'sendgrid' ? 'selected' : '' }}>SendGrid</option>
                            <option value="custom" {{ ($settings['service'] ?? '') == 'custom' ? 'selected' : '' }}>Custom API</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">API Key / List ID</label>
                        <input type="text" name="api_key" class="form-control form-control-sm" value="{{ $settings['api_key'] ?? '' }}" placeholder="Key or ID">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection