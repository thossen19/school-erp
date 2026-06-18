@extends('layouts.app')
@section('title', 'Interview Scheduling')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Interview Scheduling</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Interviews</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option></select></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Form No','Applicant','Class','Status','Interview Date','Interview Time','Actions']">
            @forelse($applications as $a)
            <tr>
                <td>{{ $loop->iteration + ($applications->currentPage()-1)*$applications->perPage() }}</td>
                <td><small class="text-muted">{{ $a->form_number }}</small></td>
                <td class="fw-semibold">{{ $a->applicant_name }}</td>
                <td>{{ $a->class_name ?? '-' }}</td>
                <td><span class="badge bg-{{ $a->status=='approved'?'info':'warning' }}">{{ ucfirst($a->status) }}</span></td>
                <td>{{ $a->interview_date ? \Carbon\Carbon::parse($a->interview_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $a->interview_time ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#interviewModal" onclick='scheduleInterview(@json($a))'><i class="fas fa-clock"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No applications pending interview</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$applications" />

<x-modal id="interviewModal" title="Schedule Interview">
    <form method="POST" action="{{ route('admissions.interview-scheduling.schedule') }}">
        @csrf
        <input type="hidden" name="form_id" id="interviewFormId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="interview_date" label="Interview Date" type="date" required /></div>
            <div class="col-md-6"><x-form-input name="interview_time" label="Interview Time" type="time" required /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#interviewModal form').submit()">Schedule</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function scheduleInterview(a) {
    $('#interviewFormId').val(a.id);
}
</script>
@endpush
@endsection
