@extends('layouts.app')
@section('title', 'General Settings')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-cog me-2"></i>General Settings</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">General Settings</li></ol></nav>
    </div>
</div>

<form method="POST" action="{{ route('settings.general.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-school me-2"></i>School Information</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">School Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $school->name ?? '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">E-Tin</label>
                            <input type="text" name="e_tin" class="form-control" value="{{ old('e_tin', $school->e_tin ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Registration No</label>
                            <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $school->registration_no ?? '') }}">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label fw-semibold small">School Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $school->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar me-2"></i>Fiscal Years</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Name</th><th>Start Date</th><th>End Date</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($academicYears as $ay)
                                <tr>
                                    <td class="fw-semibold">{{ $ay->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ay->start_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ay->end_date)->format('M d, Y') }}</td>
                                    <td>@if($ay->is_current)<span class="badge bg-success">Current</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No fiscal years defined</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        <a class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" href="#newFiscalYear"><i class="fas fa-plus me-1"></i>Add Fiscal Year</a>
                        <div class="collapse mt-2" id="newFiscalYear">
                            <div class="row g-2">
                                <div class="col-md-3"><input type="text" name="fiscal_year_name" class="form-control form-control-sm" placeholder="Name (e.g. 2026-27)"></div>
                                <div class="col-md-3"><input type="date" name="fiscal_year_start" class="form-control form-control-sm"></div>
                                <div class="col-md-3"><input type="date" name="fiscal_year_end" class="form-control form-control-sm"></div>
                                <div class="col-md-2"><div class="form-check mt-2"><input type="checkbox" class="form-check-input" name="fiscal_year_current" id="fyCurrent" value="1"><label class="form-check-label small" for="fyCurrent">Set Current</label></div></div>
                                <div class="col-md-1"><button class="btn btn-sm btn-primary">Add</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-image me-2"></i>School Logo</h6></div>
                <div class="card-body text-center">
                    @if($school->logo ?? false)
                        <img src="{{ asset('storage/'.$school->logo) }}" class="img-fluid mb-2" style="max-height:120px" alt="Logo">
                    @else
                        <div class="mb-2 text-muted"><i class="fas fa-school fa-4x"></i></div>
                    @endif
                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/jpeg,image/png">
                    <small class="text-muted d-block mt-1">JPEG/PNG, max 2MB</small>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-palette me-2"></i>Preferences</h6></div>
                <div class="card-body">
                    <div class="mb-2"><label class="form-label fw-semibold small">Theme</label><select name="theme" class="form-select form-select-sm"><option value="light" {{ ($profile->theme??'light')=='light'?'selected':'' }}>Light</option><option value="dark" {{ ($profile->theme??'')=='dark'?'selected':'' }}>Dark</option></select></div>
                    <div class="mb-2"><label class="form-label fw-semibold small">Language</label><select name="language" class="form-select form-select-sm"><option value="en" {{ ($profile->language??'en')=='en'?'selected':'' }}>English</option></select></div>
                    <div class="mb-2"><label class="form-label fw-semibold small">Timezone</label><input type="text" name="timezone" value="{{ $profile->timezone ?? 'UTC' }}" class="form-control form-control-sm"></div>
                    <div class="mb-2"><label class="form-label fw-semibold small">Date Format</label><select name="date_format" class="form-select form-select-sm"><option value="Y-m-d" {{ ($profile->date_format??'Y-m-d')=='Y-m-d'?'selected':'' }}>Y-m-d</option><option value="d/m/Y" {{ ($profile->date_format??'')=='d/m/Y'?'selected':'' }}>d/m/Y</option></select></div>

                </div>
            </div>
        </div>
    </div>

    <div class="mt-3"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Settings</button></div>
</form>
@endsection