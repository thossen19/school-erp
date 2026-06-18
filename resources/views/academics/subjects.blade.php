@extends('layouts.app')
@section('title', 'Subjects')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Subject Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Subjects</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="resetSubjectForm()"><i class="fas fa-plus me-1"></i>Add Subject</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Subject Name','Code','Type','Max Marks','Actions']">
            @foreach($subjects as $s)
            <tr>
                <td>{{ $loop->iteration + ($subjects->currentPage()-1)*$subjects->perPage() }}</td>
                <td class="fw-semibold">{{ $s->name }}</td>
                <td>{{ $s->code ?? '-' }}</td>
                <td><span class="badge bg-{{ $s->type=='core'?'primary':'info' }}">{{ ucfirst($s->type ?? 'core') }}</span></td>
                <td>{{ $s->max_marks ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-subject-btn"
                            data-id="{{ $s->id }}" data-name="{{ $s->name }}" data-code="{{ $s->code }}"
                            data-type="{{ $s->type }}" data-max-marks="{{ $s->max_marks }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.subjects.destroy', $s->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($subjects->isEmpty())
            <tr><td colspan="6" class="text-center text-muted py-4">No subjects found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$subjects" />

<x-modal id="subjectModal" title="Add Subject">
    <form method="POST" action="{{ route('academic.subjects.store') }}" id="subjectForm">
        @csrf
        <input type="hidden" name="_method" id="subjectMethodField" value="POST">
        <input type="hidden" name="subject_id" id="subjectId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Subject Name" required oninput="autoGenerateSubjectCode()" /></div>
            <div class="col-md-3"><x-form-input name="code" label="Code" required placeholder="Auto-generated" /></div>
            <div class="col-md-3"><x-form-input name="max_marks" label="Max Marks" type="number" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="type" label="Type" :options="['core'=>'Core','elective'=>'Elective']" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="subjectForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function autoGenerateSubjectCode() {
    var name = $('#name').val().trim();
    if (!name) { $('#code').val(''); return; }
    var abbr = {'mathematics':'MATH','science':'SCI','english':'ENG','hindi':'HIN','social studies':'SST','physics':'PHY','chemistry':'CHEM','biology':'BIO','computer science':'CS','physical education':'PE','art & craft':'ART','music':'MUS','sanskrit':'SAN','general knowledge':'GK','environmental studies':'EVS','english literature':'ELIT','accountancy':'ACC','economics':'ECO','business studies':'BS','history':'HIS','geography':'GEO','political science':'POL'};
    var lower = name.toLowerCase();
    if (abbr[lower]) { $('#code').val(abbr[lower]); return; }
    $('#code').val(name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 4).toUpperCase());
}
function resetSubjectForm() {
    $('#subjectModal .modal-title').text('Add Subject'); $('#subjectForm').attr('action', '{{ route('academic.subjects.store') }}');
    $('#subjectMethodField').val('POST'); $('#subjectForm')[0].reset(); $('#subjectId').val('');
    setTimeout(autoGenerateSubjectCode, 50);
}
function editSubject(s) {
    var modal = new bootstrap.Modal('#subjectModal');
    $('#subjectModal .modal-title').text('Edit Subject'); $('#subjectForm').attr('action', '{{ url('academic/subjects') }}/' + s.id);
    $('#subjectMethodField').val('PUT'); $('#subjectId').val(s.id);
    $('#name').val(s.name); $('#code').val(s.code||''); $('#type').val(s.type||'core'); $('#max_marks').val(s.max_marks||'');
    modal.show();
}
$(document).on('click', '.edit-subject-btn', function() {
    var btn = $(this);
    editSubject({ id: btn.data('id'), name: btn.data('name'), code: btn.data('code'), type: btn.data('type'), max_marks: btn.data('maxMarks') });
});
$(document).on('input', '#name', autoGenerateSubjectCode);
</script>
@endpush
@endsection
