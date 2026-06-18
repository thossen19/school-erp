@extends('layouts.app')
@section('title', 'Study Materials')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-folder me-2"></i>Study Materials</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Study Materials</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#materialModal" onclick="resetMaterialForm()"><i class="fas fa-plus me-1"></i>Add Material</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#mtSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Advanced Search <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="mtSearchCollapse">
            <form method="GET" action="{{ route('academic.study-materials') }}" class="row g-2">
                <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search title, subject, class, teacher..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="subject_id" class="form-select form-select-sm"><option value="">All Subjects</option>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="teacher_id" class="form-select form-select-sm"><option value="">All Teachers</option>@foreach($teachers as $t)<option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="type" class="form-select form-select-sm"><option value="">All Types</option><option value="document" {{ request('type')=='document'?'selected':'' }}>Document</option><option value="video" {{ request('type')=='video'?'selected':'' }}>Video</option><option value="link" {{ request('type')=='link'?'selected':'' }}>Link</option><option value="other" {{ request('type')=='other'?'selected':'' }}>Other</option></select></div>
                <div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('academic.study-materials') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a></div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Subject','Class','Teacher','Type','Actions']">
            @foreach($materials as $m)
            <tr>
                <td>{{ $loop->iteration + ($materials->currentPage()-1)*$materials->perPage() }}</td>
                <td class="fw-semibold">{{ $m->title ?? 'Untitled' }}</td>
                <td>{{ $m->subject_name ?? '-' }}</td>
                <td>{{ $m->class_name ?? '-' }}</td>
                <td>{{ $m->teacher_name ?? '-' }}</td>
                <td>{{ $m->type ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-material-btn"
                            data-id="{{ $m->id }}" data-title="{{ $m->title }}" data-class-id="{{ $m->class_id }}"
                            data-section-id="{{ $m->section_id }}" data-subject-id="{{ $m->subject_id }}"
                            data-teacher-id="{{ $m->teacher_id }}" data-description="{{ $m->description }}"
                            data-type="{{ $m->type }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.study-materials.destroy', $m->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($materials->isEmpty())
            <tr><td colspan="7" class="text-center text-muted py-4">No study materials found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$materials" />

<x-modal id="materialModal" title="Add Study Material">
    <form method="POST" action="{{ route('academic.study-materials.store') }}" id="materialForm">
        @csrf
        <input type="hidden" name="_method" id="mtMethodField" value="POST">
        <input type="hidden" name="mt_id" id="mtId">
        <div class="row g-2">
            <div class="col-md-12"><x-form-input name="title" label="Title" required placeholder="Material title" /></div>
            <div class="col-md-4"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-4"><x-form-select name="section_id" label="Section" :options="$sections->pluck('name','id')->toArray()" placeholder="All" /></div>
            <div class="col-md-4"><x-form-select name="subject_id" label="Subject" :options="$subjects->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="teacher_id" label="Teacher" :options="$teachers->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="type" label="Type" :options="['document'=>'Document','video'=>'Video','link'=>'Link','other'=>'Other']" /></div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Material description" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="materialForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetMaterialForm() {
    $('#materialModal .modal-title').text('Add Study Material');
    $('#materialForm').attr('action', '{{ route('academic.study-materials.store') }}');
    $('#mtMethodField').val('POST'); $('#materialForm')[0].reset(); $('#mtId').val('');
}
$(document).on('click', '.edit-material-btn', function() {
    var btn = $(this);
    $('#materialModal .modal-title').text('Edit Study Material');
    $('#materialForm').attr('action', '{{ url('academic/study-materials') }}/' + btn.data('id'));
    $('#mtMethodField').val('PUT'); $('#mtId').val(btn.data('id'));
    $('#title').val(btn.data('title')); $('#class_id').val(btn.data('class-id'));
    $('#section_id').val(btn.data('section-id')); $('#subject_id').val(btn.data('subject-id'));
    $('#teacher_id').val(btn.data('teacher-id')); $('#description').val(btn.data('description'));
    $('#type').val(btn.data('type'));
    var modal = new bootstrap.Modal('#materialModal');
    modal.show();
});
</script>
@endpush
@endsection