@extends('layouts.app')
@section('title', 'Student Awards')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Student Awards</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Awards</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add New</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Student', 'Award Name', 'Category', 'Date', 'Level', 'Actions']">
            @forelse($awards as $award)
            <tr>
                <td>{{ $award->id }}</td>
                <td>{{ $award->student?->name ?? 'N/A' }}</td>
                <td>{{ $award->award_name }}</td>
                <td>{{ $award->award_category ?? 'N/A' }}</td>
                <td>{{ $award->date_awarded }}</td>
                <td>{{ $award->level ?? 'N/A' }}</td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('student-awards.show', $award->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $award->id }}"><i class="fas fa-edit"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$awards" />

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('student-awards.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Award</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required>
                    <option value="">Select Student</option>
                    @foreach(\App\Models\Student\Student::all() as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>@endforeach
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Award Name</label><input type="text" name="award_name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Category</label><input type="text" name="award_category" class="form-control" placeholder="e.g. Academic, Sports"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Date Awarded</label><input type="date" name="date_awarded" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Level</label><select name="level" class="form-select">
                    <option value="">Select Level</option>
                    <option value="School">School</option><option value="District">District</option>
                    <option value="State">State</option><option value="National">National</option>
                    <option value="International">International</option>
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Edit Modals -->
@foreach($awards as $award)
<div class="modal fade" id="editModal{{ $award->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('student-awards.update', $award->id) }}">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Award</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label>
                    <p class="form-control-plaintext">{{ $award->student?->name ?? 'N/A' }}</p>
                    <input type="hidden" name="student_id" value="{{ $award->student_id }}">
                </div>
                <div class="mb-3"><label class="form-label fw-semibold">Award Name</label><input type="text" name="award_name" class="form-control" value="{{ $award->award_name }}" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Category</label><input type="text" name="award_category" class="form-control" value="{{ $award->award_category }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Date Awarded</label><input type="date" name="date_awarded" class="form-control" value="{{ $award->date_awarded }}" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Level</label><select name="level" class="form-select">
                    <option value="">Select Level</option>
                    @foreach(['School','District','State','National','International'] as $l)
                        <option value="{{ $l }}" {{ $award->level === $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2">{{ $award->remarks }}</textarea></div>
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
