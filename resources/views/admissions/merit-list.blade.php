@extends('layouts.app')
@section('title', 'Merit List')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Merit List</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Merit List</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#meritModal" onclick="resetMeritForm()"><i class="fas fa-plus me-1"></i>Add Merit List</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Class','Criteria','Status','Generated','Actions']">
            @forelse($meritLists as $m)
            <tr>
                <td>{{ $loop->iteration + ($meritLists->currentPage()-1)*$meritLists->perPage() }}</td>
                <td class="fw-semibold">{{ $m->title }}</td>
                <td>{{ $m->class_name ?? 'All' }}</td>
                <td>{{ \Str::limit($m->criteria ?? '-', 30) }}</td>
                <td><span class="badge bg-{{ $m->status=='published'?'success':'secondary' }}">{{ ucfirst($m->status) }}</span></td>
                <td>{{ $m->generated_date ? \Carbon\Carbon::parse($m->generated_date)->format('M d, Y') : '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#meritModal" onclick='editMerit(@json($m))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admissions.merit-list.destroy', $m->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No merit lists found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$meritLists" />

<x-modal id="meritModal" title="<span id='meritModalTitle'>Add Merit List</span>">
    <form method="POST" action="{{ route('admissions.merit-list.store') }}" id="meritForm">
        @csrf
        <input type="hidden" name="_method" id="meritMethodField" value="POST">
        <input type="hidden" name="merit_id" id="meritId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="title" label="Title" required /></div>
            <div class="col-md-6"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" placeholder="All Classes" /></div>
        </div>
        <x-form-textarea name="criteria" label="Criteria" rows="3" placeholder="e.g. Entrance exam score > 80%, Interview performance..." />
        <x-form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published']" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#meritForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetMeritForm() {
    $('#meritModalTitle').text('Add Merit List'); $('#meritForm').attr('action', '{{ route('admissions.merit-list.store') }}');
    $('#meritMethodField').val('POST'); $('#meritForm')[0].reset(); $('#meritId').val('');
}
function editMerit(m) {
    $('#meritModalTitle').text('Edit Merit List'); $('#meritForm').attr('action', '{{ url('admissions/merit-list/update') }}/' + m.id);
    $('#meritMethodField').val('PUT'); $('#meritId').val(m.id);
    $('#title').val(m.title); $('#class_id').val(m.class_id||''); $('#criteria').val(m.criteria||''); $('#status').val(m.status);
}
</script>
@endpush
@endsection