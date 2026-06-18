@extends('layouts.app')
@section('title', 'Medical History')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-notes-medical me-2"></i>Medical History</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Medical History</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name or admission no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Blood Group</label>
                <select name="blood_group" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search') || request('blood_group'))<a href="{{ route('health.medical-history') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No','Blood Group','Height','Weight','Physician','Insurance','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td><span class="badge bg-danger">{{ $r->blood_group ?? '-' }}</span></td>
                <td>{{ $r->height ? $r->height.' cm' : '-' }}</td>
                <td>{{ $r->weight ? $r->weight.' kg' : '-' }}</td>
                <td>{{ $r->primary_care_physician ?? '-' }}</td>
                <td>{{ $r->insurance_provider ?? '-' }}</td>
                <td>
                    <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No medical history records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

@foreach($records as $r)
<div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('health.medical-history.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Medical History - {{ $r->first_name }} {{ $r->last_name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="medTab{{ $r->id }}" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic{{ $r->id }}">Basic Info</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#medical{{ $r->id }}">Medical</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#emergency{{ $r->id }}">Emergency</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#insurance{{ $r->id }}">Insurance</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic{{ $r->id }}">
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label fw-semibold">Blood Group</label><select name="blood_group" class="form-select"><option value="">Select</option>@foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)<option value="{{ $bg }}" {{ $r->blood_group === $bg ? 'selected' : '' }}>{{ $bg }}</option>@endforeach</select></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Height (cm)</label><input type="number" step="0.01" name="height" class="form-control" value="{{ $r->height }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Weight (kg)</label><input type="number" step="0.01" name="weight" class="form-control" value="{{ $r->weight }}"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="medical{{ $r->id }}">
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label fw-semibold">Allergies</label><input type="text" name="allergies" class="form-control" value="{{ is_array($allergies = json_decode($r->allergies, true)) ? implode(', ', $allergies) : $r->allergies }}" placeholder="Comma separated"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Medical Conditions</label><input type="text" name="medical_conditions" class="form-control" value="{{ is_array($mc = json_decode($r->medical_conditions, true)) ? implode(', ', $mc) : $r->medical_conditions }}" placeholder="Comma separated"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Medications</label><input type="text" name="medications" class="form-control" value="{{ is_array($med = json_decode($r->medications, true)) ? implode(', ', $med) : $r->medications }}" placeholder="Comma separated"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Immunization Records</label><input type="text" name="immunization_records" class="form-control" value="{{ is_array($imm = json_decode($r->immunization_records, true)) ? implode(', ', $imm) : $r->immunization_records }}" placeholder="Comma separated"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Remarks</label><input type="text" name="remarks" class="form-control" value="{{ $r->remarks }}"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="emergency{{ $r->id }}">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">Emergency Contact Name</label><input type="text" name="emergency_contact_name" class="form-control" value="{{ $r->emergency_contact_name }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Emergency Contact Phone</label><input type="text" name="emergency_contact_phone" class="form-control" value="{{ $r->emergency_contact_phone }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Primary Care Physician</label><input type="text" name="primary_care_physician" class="form-control" value="{{ $r->primary_care_physician }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Physician Phone</label><input type="text" name="physician_phone" class="form-control" value="{{ $r->physician_phone }}"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="insurance{{ $r->id }}">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">Insurance Provider</label><input type="text" name="insurance_provider" class="form-control" value="{{ $r->insurance_provider }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Insurance Policy No</label><input type="text" name="insurance_policy_no" class="form-control" value="{{ $r->insurance_policy_no }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection
