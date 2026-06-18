@extends('layouts.app')
@section('title', 'Curriculum Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-sitemap me-2"></i>Curriculum Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Curriculum</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#curriculumModal" onclick="resetForm()"><i class="fas fa-plus me-1"></i>Add to Curriculum</button>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Class','Section','Subject','Type','Compulsory','Periods/Week','Total Marks','Actions']">
            @foreach($curricula as $c)
            <tr>
                <td>{{ $loop->iteration + ($curricula->currentPage()-1)*$curricula->perPage() }}</td>
                <td class="fw-semibold">{{ $c->class_name }}</td>
                <td>{{ $c->section_name ?? 'All' }}</td>
                <td>{{ $c->subject_name }} <small class="text-muted">({{ $c->subject_code }})</small></td>
                <td><span class="badge bg-{{ $c->subject_type=='core'?'primary':'info' }}">{{ ucfirst($c->subject_type ?? 'core') }}</span></td>
                <td>{!! $c->is_compulsory ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">No</span>' !!}</td>
                <td>{{ $c->max_periods_per_week }}</td>
                <td>{{ $c->total_marks }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#curriculumModal" onclick='editEntry(@json($c))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('academic.curriculum.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Delete this curriculum entry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if($curricula->isEmpty())
            <tr><td colspan="9" class="text-center text-muted py-4">No curriculum entries found. Click "Add to Curriculum" to assign subjects to classes.</td></tr>
            @endif
        </x-table>
    </div>
</div>
<x-pagination :paginator="$curricula" />

<x-modal id="curriculumModal" title="Add Curriculum Entry">
    <form method="POST" action="{{ route('academic.curriculum.store') }}" id="curriculumForm">
        @csrf
        <input type="hidden" name="_method" id="methodField" value="POST">
        <input type="hidden" name="entry_id" id="entryId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-select name="section_id" label="Section" :options="$sections->pluck('name','id')->toArray()" placeholder="All Sections" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="subject_id" label="Subject" :options="$subjects->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small mb-1">Compulsory</label>
                <div class="form-check form-switch mt-1">
                    <input type="checkbox" class="form-check-input" name="is_compulsory" id="isCompulsory" value="1" checked>
                    <label class="form-check-label small" for="isCompulsory">Yes</label>
                </div>
            </div>
            <div class="col-md-3"><x-form-input name="total_marks" label="Total Marks" type="number" value="100" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="max_periods_per_week" label="Periods/Week" type="number" value="5" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="curriculumForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetForm() {
    $('#curriculumModal .modal-title').text('Add Curriculum Entry');
    $('#curriculumForm').attr('action', '{{ route('academic.curriculum.store') }}');
    $('#methodField').val('POST');
    $('#curriculumForm')[0].reset();
    $('#entryId').val('');
    $('#isCompulsory').prop('checked', true);
}
function editEntry(c) {
    $('#curriculumModal .modal-title').text('Edit Curriculum Entry');
    $('#curriculumForm').attr('action', '{{ url('academic/curriculum') }}/' + c.id);
    $('#methodField').val('PUT');
    $('#entryId').val(c.id);
    $('#class_id').val(c.class_id || '');
    $('#section_id').val(c.section_id || '');
    $('#subject_id').val(c.subject_id || '');
    $('#isCompulsory').prop('checked', c.is_compulsory == 1 || c.is_compulsory === true);
    $('#max_periods_per_week').val(c.max_periods_per_week || 5);
    $('#total_marks').val(c.total_marks || 100);
}
</script>
@endpush
@endsection