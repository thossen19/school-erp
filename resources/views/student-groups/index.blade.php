@extends('layouts.app')
@section('title', 'Student Groups')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-users me-2"></i>Student Groups</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Groups</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#groupModal" onclick="resetGroupForm()"><i class="fas fa-plus me-1"></i>Add Group</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Name', 'Code', 'Description', 'Members', 'Status', 'Actions']">
            @forelse($groups as $group)
            <tr>
                <td>{{ $loop->iteration + ($groups->currentPage()-1)*$groups->perPage() }}</td>
                <td class="fw-semibold">{{ $group->name }}</td>
                <td><span class="badge bg-secondary">{{ $group->code }}</span></td>
                <td>{{ Str::limit($group->description, 40) ?? '-' }}</td>
                <td>{{ $group->students_count ?? 0 }}</td>
                <td><span class="badge {{ $group->status ? 'bg-success' : 'bg-secondary' }}">{{ $group->status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-group-btn"
                            data-id="{{ $group->id }}"
                            data-name="{{ $group->name }}"
                            data-code="{{ $group->code }}"
                            data-description="{{ $group->description }}"
                            data-status="{{ $group->status ? '1' : '0' }}"
                            title="Edit"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('student-groups.destroy', $group->id) }}" class="d-inline" onsubmit="return confirm('Delete this group?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No groups found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$groups" />

<x-modal id="groupModal" title="Add Group">
    <form method="POST" action="{{ route('student-groups.store') }}" id="groupForm">
        @csrf
        <input type="hidden" name="_method" id="groupMethodField" value="POST">
        <input type="hidden" name="id" id="groupId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Group Name" required placeholder="e.g. Science Club" /></div>
            <div class="col-md-6"><x-form-input name="code" label="Code" required placeholder="e.g. SCI" oninput="autoGroupCode(this)" /></div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Optional description" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="groupForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetGroupForm() {
    $('#groupModal .modal-title').text('Add Group');
    $('#groupForm').attr('action', '{{ route('student-groups.store') }}');
    $('#groupMethodField').val('POST');
    $('#groupForm')[0].reset();
    $('#groupId').val('');
    $('select[name="status"]').val('1');
}
function autoGroupCode(el) {
    var name = $('#name').val().trim();
    if (!name) { $('#code').val(''); return; }
    var abbr = {'science club':'SCI','math club':'MATH','literature club':'LIT','sports club':'SPT','art club':'ART','music club':'MUS','drama club':'DRM','debate club':'DEB','culture club':'CUL','computer club':'COM','robotics club':'RBT','environment club':'ENV','health club':'HLT','photography club':'PHO','dance club':'DNC','yoga club':'YOG','gardening club':'GRD','social service':'SOC'};
    var lower = name.toLowerCase();
    if (abbr[lower]) { $('#code').val(abbr[lower]); return; }
    $('#code').val(name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase());
}
$(document).on('click', '.edit-group-btn', function() {
    var btn = $(this);
    $('#groupModal .modal-title').text('Edit Group');
    $('#groupForm').attr('action', '{{ url('student-groups') }}/' + btn.data('id'));
    $('#groupMethodField').val('PUT');
    $('#groupId').val(btn.data('id'));
    $('#name').val(btn.data('name'));
    $('#code').val(btn.data('code'));
    $('#description').val(btn.data('description'));
    $('select[name="status"]').val(btn.data('status'));
    var modal = new bootstrap.Modal('#groupModal');
    modal.show();
});
</script>
@endpush
@endsection