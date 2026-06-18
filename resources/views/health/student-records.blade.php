@extends('layouts.app')
@section('title', 'Student Health Records')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-heartbeat me-2"></i>Student Health Records</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Student Health Records</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Record</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name or admission no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search'))<a href="{{ route('health.student-records') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No','Checkup Date','Height','Weight','BMI','Blood Pressure','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->checkup_date }}</td>
                <td>{{ $r->height ? $r->height.' cm' : '-' }}</td>
                <td>{{ $r->weight ? $r->weight.' kg' : '-' }}</td>
                <td>{{ $r->bmi ? number_format($r->bmi, 1) : '-' }}</td>
                <td>{{ $r->blood_pressure ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('health.student-records.delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No health records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('health.student-records.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Health Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Checkup Date</label><input type="date" name="checkup_date" class="form-control" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Conducted By</label><input type="text" name="conducted_by" class="form-control" placeholder="Doctor/Nurse name"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Height (cm)</label><input type="number" step="0.01" name="height" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Weight (kg)</label><input type="number" step="0.01" name="weight" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">BMI</label><input type="number" step="0.01" name="bmi" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Blood Pressure</label><input type="text" name="blood_pressure" class="form-control" placeholder="e.g. 120/80"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vision (Left)</label><input type="text" name="vision_left" class="form-control" placeholder="e.g. 20/20"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vision (Right)</label><input type="text" name="vision_right" class="form-control" placeholder="e.g. 20/20"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Dental Health</label><input type="text" name="dental_health" class="form-control" placeholder="e.g. Good, Fair, Needs attention"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Allergies</label><input type="text" name="allergies" class="form-control" placeholder="e.g. Pollen, Dust, Peanuts"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($records as $r)
<div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('health.student-records.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Health Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><p class="form-control-plaintext">{{ $r->first_name }} {{ $r->last_name }}</p><input type="hidden" name="student_id" value="{{ $r->student_id }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Checkup Date</label><input type="date" name="checkup_date" class="form-control" value="{{ $r->checkup_date }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Conducted By</label><input type="text" name="conducted_by" class="form-control" value="{{ $r->conducted_by }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Height (cm)</label><input type="number" step="0.01" name="height" class="form-control" value="{{ $r->height }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Weight (kg)</label><input type="number" step="0.01" name="weight" class="form-control" value="{{ $r->weight }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">BMI</label><input type="number" step="0.01" name="bmi" class="form-control" value="{{ $r->bmi }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Blood Pressure</label><input type="text" name="blood_pressure" class="form-control" value="{{ $r->blood_pressure }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vision (Left)</label><input type="text" name="vision_left" class="form-control" value="{{ $r->vision_left }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vision (Right)</label><input type="text" name="vision_right" class="form-control" value="{{ $r->vision_right }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Dental Health</label><input type="text" name="dental_health" class="form-control" value="{{ $r->dental_health }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Allergies</label><input type="text" name="allergies" class="form-control" value="{{ $r->allergies }}"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Notes</label><textarea name="notes" class="form-control" rows="2">{{ $r->notes }}</textarea></div>
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
