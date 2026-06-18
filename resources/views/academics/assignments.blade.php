@extends('layouts.app')
@section('title', 'Assignments')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tasks me-2"></i>Assignment Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Assignments</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignmentModal" onclick="resetAssignmentForm()"><i class="fas fa-plus me-1"></i>Add Assignment</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#asSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Advanced Search <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="asSearchCollapse">
            <form method="GET" action="{{ route('academic.assignments') }}" class="row g-2">
                <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search title, subject, class, teacher..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="subject_id" class="form-select form-select-sm"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="teacher_id" class="form-select form-select-sm"><option value="">All Teachers</option>@foreach($teachers as $t)<option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach</select></div>
                <div class="col-md-1"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Due date from"></div>
                <div class="col-md-1"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Due date to"></div>
                <div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('academic.assignments') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a></div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Subject','Class','Teacher','Due Date','Marks','Actions']">
            @foreach($assignments as $a)
            <tr>
                <td>{{ $loop->iteration + ($assignments->currentPage()-1)*$assignments->perPage() }}</td>
                <td class="fw-semibold">{{ $a->title ?? 'Untitled' }}</td>
                <td>{{ $a->subject_name ?? '-' }}</td>
                <td>{{ $a->class_name ?? '-' }}</td>
                <td>{{ $a->teacher_name ?? '-' }}</td>
                <td>{{ $a->due_date ? \Carbon\Carbon::parse($a->due_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $a->max_marks ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-assignment-btn"
                            data-id="{{ $a->id }}" data-title="{{ $a->title }}" data-class-id="{{ $a->class_id }}"
                            data-section-id="{{ $a->section_id }}" data-subject-id="{{ $a->subject_id }}"
                            data-teacher-id="{{ $a->teacher_id }}" data-description="{{ $a->description }}"
                            data-due-date="{{ $a->due_date }}" data-max-marks="{{ $a->max_marks }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.assignments.destroy', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($assignments->isEmpty())
            <tr><td colspan="8" class="text-center text-muted py-4">No assignments found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$assignments" />

<x-modal id="assignmentModal" title="Add Assignment">
    <form method="POST" action="{{ route('academic.assignments.store') }}" id="assignmentForm">
        @csrf
        <input type="hidden" name="_method" id="asMethodField" value="POST">
        <input type="hidden" name="as_id" id="asId">
        <div class="row g-2">
            <div class="col-md-12"><x-form-input name="title" label="Title" required placeholder="Assignment title" /></div>
            <div class="col-md-4"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-4"><x-form-select name="section_id" label="Section" :options="$sections->pluck('name','id')->toArray()" placeholder="All" /></div>
            <div class="col-md-4"><x-form-select name="subject_id" label="Subject" :options="$subjects->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="teacher_id" label="Teacher" :options="$teachers->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-3"><x-form-input name="due_date" label="Due Date" type="date" required /></div>
            <div class="col-md-3"><x-form-input name="max_marks" label="Max Marks" type="number" /></div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Assignment details" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="assignmentForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetAssignmentForm() {
    $('#assignmentModal .modal-title').text('Add Assignment');
    $('#assignmentForm').attr('action', '{{ route('academic.assignments.store') }}');
    $('#asMethodField').val('POST'); $('#assignmentForm')[0].reset(); $('#asId').val('');
}
$(document).on('click', '.edit-assignment-btn', function() {
    var btn = $(this);
    $('#assignmentModal .modal-title').text('Edit Assignment');
    $('#assignmentForm').attr('action', '{{ url('academic/assignments') }}/' + btn.data('id'));
    $('#asMethodField').val('PUT'); $('#asId').val(btn.data('id'));
    $('#title').val(btn.data('title')); $('#class_id').val(btn.data('class-id'));
    $('#section_id').val(btn.data('section-id')); $('#subject_id').val(btn.data('subject-id'));
    $('#teacher_id').val(btn.data('teacher-id')); $('#description').val(btn.data('description'));
    $('#due_date').val(btn.data('dueDate')); $('#max_marks').val(btn.data('maxMarks'));
    var modal = new bootstrap.Modal('#assignmentModal');
    modal.show();
});
</script>
@endpush
@endsection