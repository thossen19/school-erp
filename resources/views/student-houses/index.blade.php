@extends('layouts.app')
@section('title', 'Student Houses')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-home me-2"></i>Student Houses</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Houses</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#houseModal" onclick="resetHouseForm()"><i class="fas fa-plus me-1"></i>Add House</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Name', 'Code', 'Color', 'Motto', 'Students', 'Status', 'Actions']">
            @forelse($houses as $house)
            <tr>
                <td>{{ $loop->iteration + ($houses->currentPage()-1)*$houses->perPage() }}</td>
                <td class="fw-semibold">{{ $house->name }}</td>
                <td><span class="badge bg-secondary">{{ $house->code }}</span></td>
                <td>
                    @if($house->color)
                    <span class="badge" style="background:{{ $house->color }}">{{ $house->color }}</span>
                    @else<span class="text-muted">-</span>@endif
                </td>
                <td>{{ $house->motto ?? '-' }}</td>
                <td>{{ $house->students_count ?? 0 }}</td>
                <td><span class="badge {{ $house->status ? 'bg-success' : 'bg-secondary' }}">{{ $house->status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info edit-house-btn"
                            data-id="{{ $house->id }}"
                            data-name="{{ $house->name }}"
                            data-code="{{ $house->code }}"
                            data-color="{{ $house->color }}"
                            data-icon="{{ $house->icon }}"
                            data-motto="{{ $house->motto }}"
                            data-description="{{ $house->description }}"
                            data-status="{{ $house->status ? '1' : '0' }}"
                            title="Edit"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('student-houses.destroy', $house->id) }}" class="d-inline" onsubmit="return confirm('Delete this house?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No houses found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$houses" />

<x-modal id="houseModal" title="Add House">
    <form method="POST" action="{{ route('student-houses.store') }}" id="houseForm">
        @csrf
        <input type="hidden" name="_method" id="houseMethodField" value="POST">
        <input type="hidden" name="id" id="houseId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="House Name" required placeholder="e.g. Red House" /></div>
            <div class="col-md-6"><x-form-input name="code" label="Code" required placeholder="e.g. RED" /></div>
            <div class="col-md-4"><x-form-input name="color" label="Color" placeholder="e.g. #FF0000" /></div>
            <div class="col-md-4"><x-form-input name="icon" label="Icon" placeholder="e.g. fa-rose" /></div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-12"><x-form-input name="motto" label="Motto" placeholder="House motto" /></div>
            <div class="col-md-12"><x-form-input name="description" label="Description" placeholder="Optional description" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="houseForm">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetHouseForm() {
    $('#houseModal .modal-title').text('Add House');
    $('#houseForm').attr('action', '{{ route('student-houses.store') }}');
    $('#houseMethodField').val('POST');
    $('#houseForm')[0].reset();
    $('#houseId').val('');
    $('select[name="status"]').val('1');
}
$(document).on('click', '.edit-house-btn', function() {
    var btn = $(this);
    $('#houseModal .modal-title').text('Edit House');
    $('#houseForm').attr('action', '{{ url('student-houses') }}/' + btn.data('id'));
    $('#houseMethodField').val('PUT');
    $('#houseId').val(btn.data('id'));
    $('#name').val(btn.data('name'));
    $('#code').val(btn.data('code'));
    $('#color').val(btn.data('color'));
    $('#icon').val(btn.data('icon'));
    $('#motto').val(btn.data('motto'));
    $('#description').val(btn.data('description'));
    $('select[name="status"]').val(btn.data('status'));
    var modal = new bootstrap.Modal('#houseModal');
    modal.show();
});
</script>
@endpush
@endsection