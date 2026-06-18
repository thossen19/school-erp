@extends('layouts.app')
@section('title', 'Student Disciplines')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Student Disciplines</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Disciplines</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add New</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Student', 'Incident Date', 'Type', 'Description', 'Status', 'Actions']">
            @forelse($records as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>{{ $record->student?->first_name }} {{ $record->student?->last_name }}</td>
                <td>{{ $record->incident_date->format('d-m-Y') }}</td>
                <td>{{ $record->incident_type }}</td>
                <td>{{ Str::limit($record->description, 40) }}</td>
                <td><span class="badge {{ $record->status === 'resolved' ? 'bg-success' : ($record->status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">{{ ucfirst($record->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('student-disciplines.show', $record->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $record->id }}"><i class="fas fa-edit"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('student-disciplines.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Discipline Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required>
                    <option value="">Select Student</option>
                    @foreach(\App\Models\Student\Student::all() as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Incident Date</label><input type="date" name="incident_date" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Incident Type</label><select name="incident_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="Misbehavior">Misbehavior</option>
                    <option value="Absenteeism">Absenteeism</option>
                    <option value="Bullying">Bullying</option>
                    <option value="Cheating">Cheating</option>
                    <option value="Indiscipline">Indiscipline</option>
                    <option value="Other">Other</option>
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                    <option value="dismissed">Dismissed</option>
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Action Taken</label><textarea name="action_taken" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Edit Modals -->
@foreach($records as $record)
<div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('student-disciplines.update', $record->id) }}">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Discipline Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label>
                    <p class="form-control-plaintext">{{ $record->student?->first_name }} {{ $record->student?->last_name }}</p>
                    <input type="hidden" name="student_id" value="{{ $record->student_id }}">
                </div>
                <div class="mb-3"><label class="form-label fw-semibold">Incident Date</label><input type="date" name="incident_date" class="form-control" value="{{ $record->incident_date->format('Y-m-d') }}" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Incident Type</label><select name="incident_type" class="form-select" required>
                    @foreach(['Misbehavior','Absenteeism','Bullying','Cheating','Indiscipline','Other'] as $t)
                        <option value="{{ $t }}" {{ $record->incident_type === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ $record->description }}</textarea></div>
                <div class="mb-3"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select">
                    <option value="pending" {{ $record->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ $record->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="dismissed" {{ $record->status === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Action Taken</label><textarea name="action_taken" class="form-control" rows="2">{{ $record->action_taken }}</textarea></div>
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
