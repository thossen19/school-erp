@extends('layouts.app')
@section('title', 'Sections')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-layer-group me-2"></i>Section Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Sections</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal" onclick="resetSectionForm()"><i class="fas fa-plus me-1"></i>Add Section</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Section Name','Code','Class','Actions']">
            @foreach($sections as $s)
            <tr>
                <td>{{ $loop->iteration + ($sections->currentPage()-1)*$sections->perPage() }}</td>
                <td class="fw-semibold">{{ $s->name }}</td>
                <td class="fw-semibold">{{ $s->code }}</td>
                <td>{{ $s->class_name ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-section-btn"
                            data-id="{{ $s->id }}" data-name="{{ $s->name }}" data-code="{{ $s->code }}" data-class-id="{{ $s->class_id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.sections.destroy', $s->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($sections->isEmpty())
            <tr><td colspan="5" class="text-center text-muted py-4">No sections found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$sections" />

<x-modal id="sectionModal" title="Add Section">
    <form method="POST" action="{{ route('academic.sections.store') }}" id="sectionForm">
        @csrf
        <input type="hidden" name="_method" id="sectionMethodField" value="POST">
        <input type="hidden" name="section_id" id="sectionId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Section Name" required placeholder="e.g. A" /></div>
            <div class="col-md-6"><x-form-input name="code" label="Code" placeholder="Auto-generated" /></div>
            <div class="col-md-6"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="sectionForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
var classCodes = @json($classes->pluck('code', 'id'));
function autoGenerateSectionCode() {
    var name = $('#name').val().trim();
    var classId = $('#class_id').val();
    if (!name || !classId) { $('#code').val(''); return; }
    $('#code').val((classCodes[classId] || 'CLS') + '-' + name.toUpperCase());
}
function resetSectionForm() {
    $('#sectionModal .modal-title').text('Add Section'); $('#sectionForm').attr('action', '{{ route('academic.sections.store') }}');
    $('#sectionMethodField').val('POST'); $('#sectionForm')[0].reset(); $('#sectionId').val('');
    setTimeout(autoGenerateSectionCode, 50);
}
function editSection(s) {
    var modal = new bootstrap.Modal('#sectionModal');
    $('#sectionModal .modal-title').text('Edit Section'); $('#sectionForm').attr('action', '{{ url('academic/sections') }}/' + s.id);
    $('#sectionMethodField').val('PUT'); $('#sectionId').val(s.id);
    $('#name').val(s.name); $('#code').val(s.code); $('#class_id').val(s.class_id);
    modal.show();
}
$(document).on('click', '.edit-section-btn', function() {
    var btn = $(this);
    editSection({ id: btn.data('id'), name: btn.data('name'), code: btn.data('code'), class_id: btn.data('class-id') });
});
$(document).on('change', '#class_id', autoGenerateSectionCode);
$(document).on('input', '#name', autoGenerateSectionCode);
</script>
@endpush
@endsection
