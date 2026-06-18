@extends('layouts.app')
@section('title', 'QR Verification')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-qrcode me-2"></i>QR Verification</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Certificates</li><li class="breadcrumb-item active">QR Verification</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('certificates.qr-verify') }}" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Enter Certificate Number</label>
                <div class="input-group">
                    <input type="text" name="certificate_number" class="form-control" placeholder="e.g. CRT-2026-00001" value="{{ request('certificate_number') }}" required>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Verify</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($certificate)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white"><h5 class="mb-0 fw-semibold"><i class="fas fa-check-circle text-success me-2"></i>Verification Result</h5></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr><th style="width:200px">Certificate Number</th><td class="fw-bold font-monospace">{{ $certificate->certificate_number }}</td></tr>
                        <tr><th>Student Name</th><td>{{ $certificate->first_name }} {{ $certificate->last_name }}</td></tr>
                        <tr><th>Admission No</th><td>{{ $certificate->admission_no ?? '-' }}</td></tr>
                        <tr><th>Certificate Type</th><td><span class="badge bg-info">{{ $certificate->certificate_type }}</span></td></tr>
                        <tr><th>Template</th><td>{{ $certificate->template_name ?? '-' }}</td></tr>
                        <tr><th>Issue Date</th><td>{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d-m-Y') }}</td></tr>
                        <tr><th>Status</th><td><span class="badge bg-{{ $certificate->status === 'active' ? 'success' : ($certificate->status === 'revoked' ? 'danger' : 'warning') }}">{{ ucfirst($certificate->status) }}</span></td></tr>
                        <tr><th>QR Code</th><td>@if($certificate->qr_code)<span class="badge bg-success">Present</span>@else<span class="badge bg-secondary">Not Generated</span>@endif</td></tr>
                        <tr><th>Digital Signature</th><td>@if($certificate->digital_signature)<span class="badge bg-success">Signed</span>@else<span class="badge bg-secondary">Unsigned</span>@endif</td></tr>
                        <tr><th>Verified</th><td><span class="badge bg-{{ $certificate->verified ? 'success' : 'secondary' }}">{{ $certificate->verified ? 'Yes' : 'No' }}</span></td></tr>
                    </table>
                </div>
                <div class="col-md-4 text-center">
                    @if($certificate->qr_code)
                        <div class="border p-3 rounded d-inline-block">
                            <div class="mb-2"><i class="fas fa-qrcode fa-6x text-dark"></i></div>
                            <small class="text-muted">QR Code Data (base64)</small>
                            <div class="mt-2"><textarea class="form-control form-control-sm font-monospace" rows="4" readonly>{{ Str::limit($certificate->qr_code, 200) }}</textarea></div>
                        </div>
                    @else
                        <div class="border p-4 rounded d-inline-block text-muted">
                            <i class="fas fa-qrcode fa-6x mb-2"></i>
                            <p>QR code not generated for this certificate.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif(request('certificate_number'))
    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No certificate found with number <strong>{{ request('certificate_number') }}</strong>.</div>
@endif
@endsection
