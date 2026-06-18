@extends('layouts.app')
@section('title', 'Waiting List')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Waiting List</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Waiting List</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#waitingModal" onclick="resetWaitingForm()"><i class="fas fa-plus me-1"></i>Add to Waiting List</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Applicant','Form No','Phone','Rank','Reason','Status','Actions']">
            @forelse($waitingLists as $w)
            <tr>
                <td>{{ $loop->iteration + ($waitingLists->currentPage()-1)*$waitingLists->perPage() }}</td>
                <td class="fw-semibold">{{ $w->applicant_name }}</td>
                <td><small class="text-muted">{{ $w->form_number }}</small></td>
                <td>{{ $w->phone }}</td>
                <td><span class="badge bg-primary">{{ $w->rank }}</span></td>
                <td>{{ \Str::limit($w->reason ?? '-', 25) }}</td>
                <td><span class="badge bg-{{ $w->status=='admitted'?'success':($w->status=='cancelled'?'danger':'warning') }}">{{ ucfirst($w->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#waitingModal" onclick='editWaiting(@json($w))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admissions.waiting-list.destroy', $w->id) }}" class="d-inline" onsubmit="return confirm('Remove from waiting list?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No entries in waiting list</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$waitingLists" />

<x-modal id="waitingModal" title="<span id='waitingModalTitle'>Add to Waiting List</span>">
    <form method="POST" action="{{ route('admissions.waiting-list.store') }}" id="waitingForm">
        @csrf
        <input type="hidden" name="_method" id="waitingMethodField" value="POST">
        <input type="hidden" name="waiting_id" id="waitingId">
        <x-form-select name="admission_form_id" label="Applicant" :options="$forms->pluck('applicant_name','id')->toArray()" required />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="rank" label="Rank" type="number" required /></div>
            <div class="col-md-6"><x-form-select name="status" label="Status" :options="['waiting'=>'Waiting','admitted'=>'Admitted','cancelled'=>'Cancelled']" /></div>
        </div>
        <x-form-textarea name="reason" label="Reason" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#waitingForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetWaitingForm() {
    $('#waitingModalTitle').text('Add to Waiting List'); $('#waitingForm').attr('action', '{{ route('admissions.waiting-list.store') }}');
    $('#waitingMethodField').val('POST'); $('#waitingForm')[0].reset(); $('#waitingId').val('');
}
function editWaiting(w) {
    $('#waitingModalTitle').text('Edit Waiting List Entry'); $('#waitingForm').attr('action', '{{ url('admissions/waiting-list/update') }}/' + w.id);
    $('#waitingMethodField').val('PUT'); $('#waitingId').val(w.id);
    $('#admission_form_id').val(w.admission_form_id); $('#rank').val(w.rank);
    $('#status').val(w.status); $('#reason').val(w.reason||'');
}
</script>
@endpush
@endsection