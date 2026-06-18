@extends('layouts.app')
@section('title', 'Contact Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-address-card me-2"></i>Contact Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Contact</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.contact.update') }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-info-circle me-2"></i>Contact Details</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Street, city, zip">{{ $settings['address'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] ?? '' }}" placeholder="+1-234-567-890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}" placeholder="contact@school.edu">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-map me-2"></i>Google Map Embed</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Google Maps Embed URL</label>
                        <input type="text" name="map_embed_url" class="form-control" value="{{ $settings['map_embed_url'] ?? '' }}" placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>
                    <small class="text-muted">Paste the full embed URL from Google Maps (src attribute of the iframe).</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-share-alt me-2"></i>Contact Form</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="contact_form_enabled" value="0">
                        <input type="checkbox" name="contact_form_enabled" class="form-check-input" value="1" {{ ($settings['contact_form_enabled'] ?? '1') == '1' ? 'checked' : '' }} id="cfEnabled">
                        <label class="form-check-label" for="cfEnabled">Enable Contact Form</label>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Form Recipient Email</label>
                        <input type="email" name="form_recipient" class="form-control form-control-sm" value="{{ $settings['form_recipient'] ?? '' }}" placeholder="admin@school.edu">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection