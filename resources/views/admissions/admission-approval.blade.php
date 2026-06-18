@extends('layouts.app')
@section('title', 'Admission Approval')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-check-circle me-2"></i>Admission Approval</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Approval</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-3">
                <button class="btn btn-success btn-sm" id="bulkApproveBtn" onclick="bulkAction('approve')"><i class="fas fa-check me-1"></i>Approve Selected</button>
                <button class="btn btn-danger btn-sm" id="bulkRejectBtn" onclick="bulkAction('reject')"><i class="fas fa-times me-1"></i>Reject Selected</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <form id="bulkForm" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col"><input type="checkbox" id="selectAll"></th>
                            <th scope="col">Form No</th><th scope="col">Applicant</th><th scope="col">Class</th>
                            <th scope="col">Phone</th><th scope="col">Status</th><th scope="col">Applied Date</th><th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $a)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $a->id }}" class="select-item"></td>
                            <td><small class="text-muted">{{ $a->form_number }}</small></td>
                            <td class="fw-semibold">{{ $a->applicant_name }}</td>
                            <td>{{ $a->class_name ?? '-' }}</td>
                            <td>{{ $a->phone }}</td>
                            <td><span class="badge bg-{{ $a->status=='waiting' ? 'secondary' : 'warning' }}">{{ ucfirst($a->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($a->applied_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <form method="POST" action="{{ route('admissions.approve', $a->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></button></form>
                                    <form method="POST" action="{{ route('admissions.reject', $a->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button></form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($applications->isEmpty())
                        <tr><td colspan="8" class="text-center text-muted py-4">No pending applications</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<x-pagination :paginator="$applications" />

@push('scripts')
<script>
$('#selectAll').on('change', function() { $('.select-item').prop('checked', this.checked); });
function bulkAction(action) {
    var ids = $('.select-item:checked').map(function() { return this.value; }).get();
    if (ids.length === 0) { alert('Select at least one'); return; }
    var url = action === 'approve' ? '{{ route('admissions.admission-approval.bulk-approve') }}' : '{{ route('admissions.admission-approval.bulk-reject') }}';
    $('#bulkForm').attr('action', url).submit();
}
</script>
@endpush
@endsection
