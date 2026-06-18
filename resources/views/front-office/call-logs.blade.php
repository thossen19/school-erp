@extends('layouts.app')
@section('title', 'Call Log')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-phone-alt me-2"></i>Call Log</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Call Log</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#callLogModal" onclick="resetCallLogForm()"><i class="fas fa-plus me-1"></i>Add Call Log</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Type</label>
            <select name="call_type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="incoming" {{ request('call_type')=='incoming'?'selected':'' }}>Incoming</option>
                <option value="outgoing" {{ request('call_type')=='outgoing'?'selected':'' }}>Outgoing</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Date From</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Date To</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Caller','Phone','Type','Duration','Received By','Date','Actions']">
            @forelse($callLogs as $c)
            <tr>
                <td>{{ $loop->iteration + ($callLogs->currentPage()-1)*$callLogs->perPage() }}</td>
                <td class="fw-semibold">{{ $c->caller_name }}</td>
                <td>{{ $c->caller_phone }}</td>
                <td><span class="badge bg-{{ $c->call_type=='incoming'?'info':'secondary' }}">{{ ucfirst($c->call_type) }}</span></td>
                <td>{{ $c->duration ? gmdate('i:s', $c->duration).' min' : '-' }}</td>
                <td>{{ $c->received_by ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('M d, Y h:i A') }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#callLogModal" onclick='editCallLog(@json($c))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('front-office.call-logs.delete', $c->id) }}" class="d-inline" onsubmit="return confirm('Delete this call log?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No call logs found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$callLogs" />

<x-modal id="callLogModal" title="<span id='callLogModalTitle'>Add Call Log</span>">
    <form method="POST" action="{{ route('front-office.call-logs.store') }}" id="callLogForm">
        @csrf
        <input type="hidden" name="_method" id="callLogMethodField" value="POST">
        <input type="hidden" name="call_log_id" id="callLogId">
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="caller_name" label="Caller Name" required /></div>
            <div class="col-md-4"><x-form-input name="caller_phone" label="Caller Phone" required /></div>
            <div class="col-md-2"><x-form-select name="call_type" label="Type" :options="['incoming'=>'Incoming','outgoing'=>'Outgoing']" required /></div>
            <div class="col-md-2"><x-form-input name="duration" label="Duration (sec)" type="number" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="callee_name" label="Callee Name" /></div>
            <div class="col-md-4"><x-form-input name="callee_phone" label="Callee Phone" /></div>
            <div class="col-md-4"><x-form-input name="received_by" label="Received By" /></div>
        </div>
        <x-form-input name="purpose" label="Purpose" />
        <x-form-textarea name="notes" label="Notes" rows="2" />
        <x-form-input name="follow_up_date" label="Follow Up Date" type="date" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#callLogForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetCallLogForm() {
    $('#callLogModalTitle').text('Add Call Log');
    $('#callLogForm').attr('action', '{{ route('front-office.call-logs.store') }}');
    $('#callLogMethodField').val('POST');
    $('#callLogForm')[0].reset();
    $('#callLogId').val('');
}
function editCallLog(c) {
    $('#callLogModalTitle').text('Edit Call Log');
    $('#callLogForm').attr('action', '{{ url('front-office/call-logs/update') }}/' + c.id);
    $('#callLogMethodField').val('PUT');
    $('#callLogId').val(c.id);
    $('#caller_name').val(c.caller_name);
    $('#caller_phone').val(c.caller_phone);
    $('#call_type').val(c.call_type);
    $('#duration').val(c.duration||'');
    $('#callee_name').val(c.callee_name||'');
    $('#callee_phone').val(c.callee_phone||'');
    $('#received_by').val(c.received_by||'');
    $('#purpose').val(c.purpose||'');
    $('#notes').val(c.notes||'');
    $('#follow_up_date').val(c.follow_up_date||'');
}
</script>
@endpush
@endsection