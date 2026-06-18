@extends('layouts.app')
@section('title', 'Vaccination Records')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-syringe me-2"></i>Vaccination Records</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Vaccination Records</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Record Vaccination</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student or vaccine name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Filter</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming Due</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search') || request('status'))<a href="{{ route('health.vaccinations') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No','Vaccine','Dose #','Date Given','Next Due','Administered By','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->vaccine_name }}</td>
                <td>{{ $r->dose_number }}</td>
                <td>{{ $r->vaccination_date }}</td>
                <td>{{ $r->next_due_date ?? '-' }}</td>
                <td>{{ $r->administered_by ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('health.vaccinations.delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this vaccination record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No vaccination records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('health.vaccinations.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Record Vaccination</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach(\App\Models\Student\Student::where('school_id', session('school_id', 1))->get() as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Vaccine Name</label><input type="text" name="vaccine_name" class="form-control" required placeholder="e.g. Hepatitis B"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Dose Number</label><input type="number" name="dose_number" class="form-control" value="1" min="1"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vaccination Date</label><input type="date" name="vaccination_date" class="form-control" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Next Due Date</label><input type="date" name="next_due_date" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Administered By</label><input type="text" name="administered_by" class="form-control" placeholder="Doctor/Nurse name"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Batch Number</label><input type="text" name="batch_number" class="form-control" placeholder="Batch/Lot #"></div>
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
        <form method="POST" action="{{ route('health.vaccinations.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Vaccination</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><p class="form-control-plaintext">{{ $r->first_name }} {{ $r->last_name }}</p><input type="hidden" name="student_id" value="{{ $r->student_id }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Vaccine Name</label><input type="text" name="vaccine_name" class="form-control" value="{{ $r->vaccine_name }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Dose Number</label><input type="number" name="dose_number" class="form-control" value="{{ $r->dose_number }}" min="1"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Vaccination Date</label><input type="date" name="vaccination_date" class="form-control" value="{{ $r->vaccination_date }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Next Due Date</label><input type="date" name="next_due_date" class="form-control" value="{{ $r->next_due_date }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Administered By</label><input type="text" name="administered_by" class="form-control" value="{{ $r->administered_by }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Batch Number</label><input type="text" name="batch_number" class="form-control" value="{{ $r->batch_number }}"></div>
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
