@extends('layouts.app')
@section('title', 'Teacher Diary')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Teacher Diary</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Teacher Diary</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#diaryModal" onclick="resetDiaryForm()"><i class="fas fa-plus me-1"></i>Add Entry</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#diarySearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Advanced Search <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="diarySearchCollapse">
            <form method="GET" action="{{ route('academic.teacher-diary') }}" class="row g-2">
                <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search topic, lesson, subject, class, teacher..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="subject_id" class="form-select form-select-sm"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="teacher_id" class="form-select form-select-sm"><option value="">All Teachers</option>@foreach($teachers as $t)<option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach</select></div>
                <div class="col-md-1"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Date from"></div>
                <div class="col-md-1"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Date to"></div>
                <div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('academic.teacher-diary') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a></div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Date','Teacher','Class','Section','Subject','Topic','Actions']">
            @foreach($diaries as $d)
            <tr>
                <td>{{ $loop->iteration + ($diaries->currentPage()-1)*$diaries->perPage() }}</td>
                <td>{{ \Carbon\Carbon::parse($d->date)->format('M d, Y') }}</td>
                <td class="fw-semibold">{{ $d->teacher_name ?? '-' }}</td>
                <td>{{ $d->class_name ?? '-' }}</td>
                <td>{{ $d->section_name ?? '-' }}</td>
                <td>{{ $d->subject_name ?? '-' }}</td>
                <td>{{ \Str::limit($d->topic ?? 'N/A', 30) }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-diary-btn"
                            data-id="{{ $d->id }}" data-date="{{ $d->date }}" data-class-id="{{ $d->class_id }}"
                            data-section-id="{{ $d->section_id }}" data-subject-id="{{ $d->subject_id }}"
                            data-teacher-id="{{ $d->teacher_id }}" data-topic="{{ $d->topic }}"
                            data-lesson-taught="{{ $d->lesson_taught }}" data-student-participation="{{ $d->student_participation }}"
                            data-remarks="{{ $d->remarks }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.teacher-diary.destroy', $d->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($diaries->isEmpty())
            <tr><td colspan="8" class="text-center text-muted py-4">No diary entries</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$diaries" />

<x-modal id="diaryModal" title="Add Diary Entry">
    <form method="POST" action="{{ route('academic.teacher-diary.store') }}" id="diaryForm">
        @csrf
        <input type="hidden" name="_method" id="diaryMethodField" value="POST">
        <input type="hidden" name="diary_id" id="diaryId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="subject_id" label="Subject" :options="$subjects->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-input name="date" label="Date" type="date" required /></div>
            <div class="col-md-6"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="section_id" label="Section" :options="$sections->pluck('name','id')->toArray()" placeholder="Optional" /></div>
            <div class="col-md-6"><x-form-select name="teacher_id" label="Teacher" :options="$teachers->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-input name="topic" label="Topic" /></div>
        </div>
        <x-form-textarea name="lesson_taught" label="Lesson Taught" rows="3" />
        <x-form-textarea name="student_participation" label="Student Participation" rows="2" />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="diaryForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetDiaryForm() {
    $('#diaryModal .modal-title').text('Add Diary Entry');
    $('#diaryForm').attr('action', '{{ route('academic.teacher-diary.store') }}');
    $('#diaryMethodField').val('POST'); $('#diaryForm')[0].reset(); $('#diaryId').val('');
}
$(document).on('click', '.edit-diary-btn', function() {
    var btn = $(this);
    $('#diaryModal .modal-title').text('Edit Diary Entry');
    $('#diaryForm').attr('action', '{{ url('academic/teacher-diary') }}/' + btn.data('id'));
    $('#diaryMethodField').val('PUT'); $('#diaryId').val(btn.data('id'));
    $('#date').val(btn.data('date')); $('#class_id').val(btn.data('class-id'));
    $('#section_id').val(btn.data('section-id')); $('#subject_id').val(btn.data('subject-id'));
    $('#teacher_id').val(btn.data('teacher-id')); $('#topic').val(btn.data('topic'));
    $('#lesson_taught').val(btn.data('lessonTaught'));
    $('#student_participation').val(btn.data('studentParticipation'));
    $('#remarks').val(btn.data('remarks'));
    var modal = new bootstrap.Modal('#diaryModal');
    modal.show();
});
</script>
@endpush
@endsection