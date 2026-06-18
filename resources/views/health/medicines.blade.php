@extends('layouts.app')
@section('title', 'Medicine Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-pills me-2"></i>Medicine Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Medicine Tracking</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Medicine</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student or medicine name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search'))<a href="{{ route('health.medicines') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Medicine','Dosage','Frequency','Start Date','End Date','Prescribed By','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->medicine_name }}</td>
                <td>{{ $r->dosage ?? '-' }}</td>
                <td>{{ $r->frequency ?? '-' }}</td>
                <td>{{ $r->start_date }}</td>
                <td>{{ $r->end_date ?? 'Ongoing' }}</td>
                <td>{{ $r->prescribed_by ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('health.medicines.delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this medicine record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No medicine records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('health.medicines.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Medicine Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach(\App\Models\Student\Student::where('school_id', session('school_id', 1))->get() as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Medicine Name</label><input type="text" name="medicine_name" class="form-control" required placeholder="e.g. Paracetamol"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Dosage</label><input type="text" name="dosage" class="form-control" placeholder="e.g. 500mg"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Strength</label><input type="text" name="strength" class="form-control" placeholder="e.g. 250mg/5ml"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Frequency</label><input type="text" name="frequency" class="form-control" placeholder="e.g. Twice daily"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Prescribed By</label><input type="text" name="prescribed_by" class="form-control" placeholder="Doctor name"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Start Date</label><input type="date" name="start_date" class="form-control" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">End Date</label><input type="date" name="end_date" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
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
        <form method="POST" action="{{ route('health.medicines.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Medicine</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><p class="form-control-plaintext">{{ $r->first_name }} {{ $r->last_name }}</p><input type="hidden" name="student_id" value="{{ $r->student_id }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Medicine Name</label><input type="text" name="medicine_name" class="form-control" value="{{ $r->medicine_name }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Dosage</label><input type="text" name="dosage" class="form-control" value="{{ $r->dosage }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Strength</label><input type="text" name="strength" class="form-control" value="{{ $r->strength }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Frequency</label><input type="text" name="frequency" class="form-control" value="{{ $r->frequency }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Prescribed By</label><input type="text" name="prescribed_by" class="form-control" value="{{ $r->prescribed_by }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ $r->start_date }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">End Date</label><input type="date" name="end_date" class="form-control" value="{{ $r->end_date }}"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2">{{ $r->remarks }}</textarea></div>
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
