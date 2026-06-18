@extends('layouts.app')
@section('title', 'Leave Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plane me-2"></i>Leave Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Leave Tracking</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    @php $statusColors = ['approved'=>'success','pending'=>'warning text-dark','rejected'=>'danger']; @endphp
    @foreach($summary as $s)
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-{{ $statusColors[$s->status] ?? 'primary' }}">{{ $s->total }}</h5><small class="text-muted">{{ ucfirst($s->status) }}</small></div></div>
    @endforeach
</div>
<div class="row g-2">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Leave Requests</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Leave Type','Start','End','Status','Created']">
                    @foreach($leaveRequests as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->leave_type_name ?? '-' }}</td>
                        <td>{{ $r->start_date }}</td>
                        <td>{{ $r->end_date }}</td>
                        <td><span class="badge bg-{{ $statusColors[$r->status] ?? 'secondary' }}">{{ ucfirst($r->status) }}</span></td>
                        <td>{{ $r->created_at }}</td>
                    </tr>
                    @endforeach
                    @if($leaveRequests->isEmpty())<tr><td colspan="6" class="text-center text-muted py-3">No leave requests</td></tr>@endif
                </x-table>
            </div>
            <x-pagination :paginator="$leaveRequests" />
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-balance-scale me-2"></i>Leave Balances</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Leave Type','Total','Used','Remaining']">
                    @foreach($leaveBalances as $b)
                    <tr>
                        <td>{{ $b->leave_type_name ?? '-' }}</td>
                        <td>{{ $b->total_days }}</td>
                        <td>{{ $b->used_days }}</td>
                        <td><span class="badge bg-{{ $b->remaining_days > 0 ? 'success' : 'danger' }}">{{ $b->remaining_days }}</span></td>
                    </tr>
                    @endforeach
                    @if($leaveBalances->isEmpty())<tr><td colspan="4" class="text-center text-muted py-3">No leave balances</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
