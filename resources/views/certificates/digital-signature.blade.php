@extends('layouts.app')
@section('title', 'Digital Signature')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-signature me-2"></i>Digital Signature</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Certificates</li><li class="breadcrumb-item active">Digital Signature</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <p class="text-muted mb-0">Manage digital signatures for certificates. Unsigned certificates are shown first.</p>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Certificate No','Student','Certificate Type','Issue Date','Signature Status','Verified','Actions']">
            @forelse($certificates as $c)
            <tr class="{{ $c->digital_signature ? '' : 'table-warning' }}">
                <td>{{ $c->id }}</td>
                <td class="fw-semibold font-monospace">{{ $c->certificate_number }}</td>
                <td>{{ $c->first_name }} {{ $c->last_name }}<br><small class="text-muted">{{ $c->admission_no ?? '' }}</small></td>
                <td>{{ $c->certificate_type }}</td>
                <td>{{ \Carbon\Carbon::parse($c->issue_date)->format('d-m-Y') }}</td>
                <td>
                    @if($c->digital_signature)
                        <span class="badge bg-success">Signed</span>
                    @else
                        <span class="badge bg-warning text-dark">Unsigned</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $c->verified ? 'success' : 'secondary' }}">{{ $c->verified ? 'Yes' : 'No' }}</span>
                </td>
                <td>
                    @if(!$c->digital_signature)
                        <button class="btn btn-sm btn-outline-success" title="Add Signature" data-bs-toggle="modal" data-bs-target="#signModal{{ $c->id }}"><i class="fas fa-signature"></i> Sign</button>
                    @else
                        <button class="btn btn-sm btn-outline-info" title="View Signature" data-bs-toggle="modal" data-bs-target="#viewSignModal{{ $c->id }}"><i class="fas fa-eye"></i></button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No certificates found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$certificates" />

@foreach($certificates as $c)
@if(!$c->digital_signature)
<div class="modal fade" id="signModal{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('certificates.digital-signature.add', $c->id) }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-file-signature me-1"></i>Add Digital Signature</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Certificate</label><p class="form-control-plaintext font-monospace">{{ $c->certificate_number }} — {{ $c->first_name }} {{ $c->last_name }}</p></div>
                <div class="mb-3"><label class="form-label fw-semibold">Signature Data</label><textarea name="signature_data" class="form-control" rows="4" required placeholder="Enter or paste the digital signature data (e.g. base64-encoded signature, or signed hash)"></textarea></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="confirmSign{{ $c->id }}" required><label class="form-check-label" for="confirmSign{{ $c->id }}">I confirm this signature is authentic and authorized</label></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-signature me-1"></i>Apply Signature</button>
            </div>
        </form>
    </div></div>
</div>
@else
<div class="modal fade" id="viewSignModal{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title"><i class="fas fa-file-signature me-1"></i>Digital Signature Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label fw-semibold">Certificate</label><p class="form-control-plaintext font-monospace">{{ $c->certificate_number }} — {{ $c->first_name }} {{ $c->last_name }}</p></div>
            <div class="mb-3"><label class="form-label fw-semibold">Verified</label><p class="form-control-plaintext"><span class="badge bg-{{ $c->verified ? 'success' : 'secondary' }}">{{ $c->verified ? 'Yes' : 'No' }}</span></p></div>
            <div class="mb-3"><label class="form-label fw-semibold">Signature Data</label><textarea class="form-control font-monospace" rows="6" readonly>{{ $c->digital_signature }}</textarea></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div></div>
</div>
@endif
@endforeach
@endsection
