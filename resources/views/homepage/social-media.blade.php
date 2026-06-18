@extends('layouts.app')
@section('title', 'Social Media')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-share-alt me-2"></i>Social Media</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Social Media</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.social-media.update') }}">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-link me-2"></i>Social Media Links</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-facebook-f fa-2x text-primary"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">Facebook</label>
                            <input type="text" name="facebook" class="form-control form-control-sm" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-linkedin-in fa-2x" style="color:#0A66C2"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">LinkedIn</label>
                            <input type="text" name="linkedin" class="form-control form-control-sm" value="{{ $settings['linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-youtube fa-2x text-danger"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">YouTube</label>
                            <input type="text" name="youtube" class="form-control form-control-sm" value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-x-twitter fa-2x text-dark"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">X (Twitter)</label>
                            <input type="text" name="twitter" class="form-control form-control-sm" value="{{ $settings['twitter'] ?? '' }}" placeholder="https://x.com/...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-instagram fa-2x" style="color:#E1306C"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">Instagram</label>
                            <input type="text" name="instagram" class="form-control form-control-sm" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded d-flex align-items-center gap-3">
                        <i class="fab fa-whatsapp fa-2x text-success"></i>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold small mb-0">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control form-control-sm" value="{{ $settings['whatsapp'] ?? '' }}" placeholder="https://wa.me/...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection