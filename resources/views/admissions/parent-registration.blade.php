@extends('layouts.app')
@section('title', 'Parent Registration')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-friends me-2"></i>Parent Registration</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Parents</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#parentModal" onclick="resetParentForm()"><i class="fas fa-plus me-1"></i>Add Parent</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search name / phone..." value="{{ request('search') }}"></div>
            <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Phone','Email','Occupation','Student','Relationship','Actions']">
            @forelse($parents as $p)
            <tr>
                <td>{{ $loop->iteration + ($parents->currentPage()-1)*$parents->perPage() }}</td>
                <td class="fw-semibold">{{ $p->first_name }} {{ $p->last_name ?? '' }}</td>
                <td>{{ $p->phone }}</td>
                <td>{{ $p->email ?? '-' }}</td>
                <td>{{ $p->occupation ?? '-' }}</td>
                <td>{{ $p->student_name ?? '-' }}</td>
                <td>{{ $p->relationship ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#parentModal" onclick='editParent(@json($p))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admissions.parent-registration.destroy', $p->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No parents found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$parents" />

<x-modal id="parentModal" title="<span id='parentModalTitle'>Add Parent</span>">
    <form method="POST" action="{{ route('admissions.parent-registration.store') }}" id="parentForm">
        @csrf
        <input type="hidden" name="_method" id="parentMethodField" value="POST">
        <input type="hidden" name="parent_id" id="parentId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="first_name" label="First Name" required /></div>
            <div class="col-md-6"><x-form-input name="last_name" label="Last Name" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="email" label="Email" /></div>
            <div class="col-md-4"><x-form-input name="occupation" label="Occupation" /></div>
        </div>
        <x-form-textarea name="address" label="Address" rows="2" />
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="student_id" label="Link to Student" :options="$students->pluck('first_name','id')->toArray()" placeholder="Optional" /></div>
            <div class="col-md-6"><x-form-select name="relationship" label="Relationship" :options="['father'=>'Father','mother'=>'Mother','guardian'=>'Guardian','other'=>'Other']" placeholder="Optional" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#parentForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetParentForm() {
    $('#parentModalTitle').text('Add Parent'); $('#parentForm').attr('action', '{{ route('admissions.parent-registration.store') }}');
    $('#parentMethodField').val('POST'); $('#parentForm')[0].reset(); $('#parentId').val('');
}
function editParent(p) {
    $('#parentModalTitle').text('Edit Parent'); $('#parentForm').attr('action', '{{ url('admissions/parent-registration/update') }}/' + p.id);
    $('#parentMethodField').val('PUT'); $('#parentId').val(p.id);
    $('#first_name').val(p.first_name); $('#last_name').val(p.last_name||'');
    $('#phone').val(p.phone); $('#email').val(p.email||''); $('#occupation').val(p.occupation||'');
    $('#address').val(p.address||'');
}
</script>
@endpush
@endsection
