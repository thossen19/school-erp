@extends('layouts.app')
@section('title', 'Classes')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chalkboard me-2"></i>Class Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Classes</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#classModal" onclick="resetClassForm()"><i class="fas fa-plus me-1"></i>Add Class</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Class Name','Code','Level','Capacity','Actions']">
            @foreach($classes as $c)
            <tr>
                <td>{{ $loop->iteration + ($classes->currentPage()-1)*$classes->perPage() }}</td>
                <td class="fw-semibold">{{ $c->name }}</td>
                <td>{{ $c->code ?? '-' }}</td>
                <td>{{ $c->education_level ?? '-' }}</td>
                <td>{{ $c->capacity ?? 0 }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-class-btn"
                            data-id="{{ $c->id }}" data-name="{{ $c->name }}" data-code="{{ $c->code }}"
                            data-numeric-value="{{ $c->numeric_value }}" data-education-level="{{ $c->education_level }}"
                            data-capacity="{{ $c->capacity }}" data-description="{{ $c->description }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.classes.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($classes->isEmpty())
            <tr><td colspan="6" class="text-center text-muted py-4">No classes found</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$classes" />

<x-modal id="classModal" title="Add Class">
    <form method="POST" action="{{ route('academic.classes.store') }}" id="classForm">
        @csrf
        <input type="hidden" name="_method" id="classMethodField" value="POST">
        <input type="hidden" name="class_id" id="classId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Class Name" required placeholder="e.g. Grade 1" oninput="autoGenerateCode()" /></div>
            <div class="col-md-6"><x-form-input name="code" label="Code" required placeholder="Auto-generated" /></div>
            <div class="col-md-3"><x-form-input name="numeric_value" label="Numeric Value" type="number" placeholder="e.g. 1" /></div>
            <div class="col-md-3"><x-form-input name="education_level" label="Education Level" placeholder="e.g. Primary" /></div>
            <div class="col-md-3"><x-form-input name="capacity" label="Capacity" type="number" placeholder="e.g. 40" /></div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Optional description" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="classForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function autoGenerateCode() {
    var name = $('#name').val().trim();
    if (!name) { $('#code').val(''); return; }
    var match = name.match(/(?:Class|Grade)\s*(\d+)/i);
    if (match) {
        var num = parseInt(match[1]);
        $('#code').val('C' + String(num).padStart(2, '0'));
        return;
    }
    var abbr = {'nursery':'NUR','lkg':'LKG','ukg':'UKG','playgroup':'PG','kindergarten':'KG'};
    var lower = name.toLowerCase();
    if (abbr[lower]) { $('#code').val(abbr[lower]); return; }
    $('#code').val(name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase());
}
function resetClassForm() {
    $('#classModal .modal-title').text('Add Class'); $('#classForm').attr('action', '{{ route('academic.classes.store') }}');
    $('#classMethodField').val('POST'); $('#classForm')[0].reset(); $('#classId').val('');
    setTimeout(autoGenerateCode, 50);
}
$(document).on('click', '.edit-class-btn', function() {
    var btn = $(this);
    $('#classModal .modal-title').text('Edit Class');
    $('#classForm').attr('action', '{{ url('academic/classes') }}/' + btn.data('id'));
    $('#classMethodField').val('PUT'); $('#classId').val(btn.data('id'));
    $('#name').val(btn.data('name')); $('#code').val(btn.data('code'));
    $('#numeric_value').val(btn.data('numericValue')); $('#education_level').val(btn.data('educationLevel'));
    $('#capacity').val(btn.data('capacity')); $('#description').val(btn.data('description'));
    var modal = new bootstrap.Modal('#classModal');
    modal.show();
});
</script>
@endpush
@endsection