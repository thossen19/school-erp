@extends('layouts.app')
@section('title', 'School Settings')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-school me-2"></i>School Settings</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li>
                <li class="breadcrumb-item active">School Settings</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('settings.school.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>School Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">School Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $school->name ?? '') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">School Short Code</label>
                            <input type="text" name="short_code" class="form-control" value="{{ old('short_code', $school->short_code ?? '') }}" placeholder="e.g. FZIS" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">School Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $school->code ?? '') }}" placeholder="e.g. DIS2024">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">E-Tin / Tax ID</label>
                            <input type="text" name="e_tin" class="form-control" value="{{ old('e_tin', $school->e_tin ?? '') }}" placeholder="Tax identification number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Registration No.</label>
                            <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $school->registration_no ?? '') }}" placeholder="School registration number">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-address-book me-2 text-success"></i>Contact Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone ?? '') }}" placeholder="e.g. +91-9876543210">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $school->email ?? '') }}" placeholder="info@school.edu">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Website</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                <input type="url" name="website" class="form-control" value="{{ old('website', $school->website ?? '') }}" placeholder="https://school.edu">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Address</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Street Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Street, area, landmark">{{ old('address', $school->address ?? '') }}</textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $school->city ?? '') }}" placeholder="e.g. New Delhi">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $school->state ?? '') }}" placeholder="e.g. Delhi">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $school->country ?? '') }}" placeholder="e.g. India">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Pincode</label>
                            <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $school->pincode ?? '') }}" placeholder="e.g. 110001">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-image me-2 text-warning"></i>School Logo</h6>
                </div>
                <div class="card-body text-center">
                    @if($school->logo ?? false)
                        <div class="mb-3 p-3 bg-light rounded d-inline-block">
                            <img src="{{ asset('storage/'.$school->logo) }}" class="img-fluid" style="max-height:120px" alt="School Logo">
                        </div>
                    @else
                        <div class="mb-3 text-muted py-4">
                            <i class="fas fa-school fa-5x"></i>
                            <p class="mt-2 small text-muted">No logo uploaded</p>
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg" id="logoInput">
                    <small class="text-muted d-block mt-1">JPEG/PNG, max 2MB</small>
                    <div id="logoPreview" class="mt-2 d-none">
                        <img class="img-fluid rounded border" style="max-height:80px" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-check-circle me-2 text-info"></i>School Status</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($school->status ?? true)
                            <span class="badge bg-success fs-6 px-3 py-2"><i class="fas fa-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                    <p class="small text-muted mb-0">School status determines whether the institution is accepting new admissions and operating normally.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i>Save Changes</button>
        <a href="{{ route_if_exists('settings.general') }}" class="btn btn-outline-secondary px-4"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('logoInput')?.addEventListener('change', function(e) {
    var preview = document.getElementById('logoPreview');
    var img = preview.querySelector('img');
    if (e.target.files && e.target.files[0]) {
        img.src = URL.createObjectURL(e.target.files[0]);
        preview.classList.remove('d-none');
    } else {
        preview.classList.add('d-none');
    }
});
</script>
@endpush