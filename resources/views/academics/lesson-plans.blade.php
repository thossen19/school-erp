@extends('layouts.app')
@section('title', 'Lesson Plans')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Lesson Plans</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Lesson Plans</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lessonPlanModal" onclick="resetLessonPlanForm()"><i class="fas fa-plus me-1"></i>Add Lesson Plan</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#lpSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Advanced Search <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="lpSearchCollapse">
            <form method="GET" action="{{ route('academic.lesson-plans') }}" class="row g-2">
                <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search title, subject, class, teacher..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="subject_id" class="form-select form-select-sm"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="teacher_id" class="form-select form-select-sm"><option value="">All Teachers</option>@foreach($teachers as $t)<option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach</select></div>
                <div class="col-md-1"><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option><option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option></select></div>
                <div class="col-md-1"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Date from"></div>
                <div class="col-md-1"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Date to"></div>
                <div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('academic.lesson-plans') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a></div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Subject','Class','Section','Teacher','Duration','Status','Created','Actions']">
            @foreach($lessonPlans as $lp)
            <tr>
                <td>{{ $loop->iteration + ($lessonPlans->currentPage()-1)*$lessonPlans->perPage() }}</td>
                <td class="fw-semibold">{{ $lp->title ?? 'Untitled' }}</td>
                <td>{{ $lp->subject_name ?? '-' }}</td>
                <td>{{ $lp->class_name ?? '-' }}</td>
                <td>{{ $lp->section_name ?? '-' }}</td>
                <td>{{ $lp->teacher_name ?? '-' }}</td>
                <td>{{ $lp->duration ? $lp->duration . ' min' : '-' }}</td>
                <td><span class="badge bg-{{ $lp->status=='published'?'success':'warning' }}">{{ ucfirst($lp->status ?? 'draft') }}</span></td>
                <td>{{ \Carbon\Carbon::parse($lp->created_at)->format('M d, Y') }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-lp-btn"
                            data-id="{{ $lp->id }}" data-title="{{ $lp->title }}"
                            data-class-id="{{ $lp->class_id }}" data-section-id="{{ $lp->section_id }}"
                            data-subject-id="{{ $lp->subject_id }}" data-teacher-id="{{ $lp->teacher_id }}"
                            data-description="{{ $lp->description }}" data-objectives="{{ $lp->objectives }}"
                            data-materials="{{ $lp->materials }}" data-activities="{{ $lp->activities }}"
                            data-assessment-method="{{ $lp->assessment_method }}"
                            data-duration="{{ $lp->duration }}" data-status="{{ $lp->status }}"
                            data-lesson-content="{{ $lp->lesson_content }}" data-lesson-date="{{ $lp->lesson_date }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.lesson-plans.destroy', $lp->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($lessonPlans->isEmpty())
            <tr><td colspan="10" class="text-center text-muted py-4">No lesson plans found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$lessonPlans" />

<x-modal id="lessonPlanModal" title="Add Lesson Plan">
    <form method="POST" action="{{ route('academic.lesson-plans.store') }}" id="lessonPlanForm">
        @csrf
        <input type="hidden" name="_method" id="lpMethodField" value="POST">
        <input type="hidden" name="lp_id" id="lpId">
        <div class="row g-2">
            <div class="col-md-12"><x-form-input name="title" label="Title" required placeholder="Lesson title" /></div>
            <div class="col-md-4"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-4"><x-form-select name="section_id" label="Section" :options="$sections->pluck('name','id')->toArray()" placeholder="Optional" /></div>
            <div class="col-md-4"><x-form-select name="subject_id" label="Subject" :options="$subjects->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="teacher_id" label="Teacher" :options="$teachers->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-3"><x-form-input name="duration" label="Duration (min)" type="number" placeholder="e.g. 45" /></div>
            <div class="col-md-3"><x-form-input name="lesson_date" label="Date" type="date" /></div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Brief description" /></div>
            <div class="col-md-12"><x-form-input name="objectives" label="Objectives" placeholder="Learning objectives" /></div>
            <div class="col-md-12"><x-form-input name="materials" label="Materials" placeholder="Required materials" /></div>
            <div class="col-md-12"><x-form-input name="activities" label="Activities" placeholder="Class activities" /></div>
            <div class="col-md-12"><x-form-input name="assessment_method" label="Assessment Method" placeholder="How learning is assessed" /></div>
            <div class="col-md-12"><x-form-input name="lesson_content" label="Lesson Content" placeholder="Lesson content / notes" /></div>
            <div class="col-md-6"><x-form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published']" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="lessonPlanForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetLessonPlanForm() {
    $('#lessonPlanModal .modal-title').text('Add Lesson Plan');
    $('#lessonPlanForm').attr('action', '{{ route('academic.lesson-plans.store') }}');
    $('#lpMethodField').val('POST'); $('#lessonPlanForm')[0].reset(); $('#lpId').val('');
}
$(document).on('click', '.edit-lp-btn', function() {
    var btn = $(this);
    $('#lessonPlanModal .modal-title').text('Edit Lesson Plan');
    $('#lessonPlanForm').attr('action', '{{ url('academic/lesson-plans') }}/' + btn.data('id'));
    $('#lpMethodField').val('PUT'); $('#lpId').val(btn.data('id'));
    $('#title').val(btn.data('title'));
    $('#class_id').val(btn.data('class-id'));
    $('#section_id').val(btn.data('section-id'));
    $('#subject_id').val(btn.data('subject-id'));
    $('#teacher_id').val(btn.data('teacher-id'));
    $('#description').val(btn.data('description'));
    $('#objectives').val(btn.data('objectives'));
    $('#materials').val(btn.data('materials'));
    $('#activities').val(btn.data('activities'));
    $('#assessment_method').val(btn.data('assessment-method'));
    $('#duration').val(btn.data('duration'));
    $('#status').val(btn.data('status'));
    $('#lesson_content').val(btn.data('lessonContent'));
    $('#lesson_date').val(btn.data('lessonDate'));
    var modal = new bootstrap.Modal('#lessonPlanModal');
    modal.show();
});
</script>
@endpush
@endsection