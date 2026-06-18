@extends('layouts.app')
@section('title', 'Attendance Correction')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Attendance Correction</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Attendance Correction</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Class','Attendance Date','Old Status','New Status','Reason','Correction Status','Requested At']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->attendance_date ?? '-' }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($r->old_status) }}</span></td>
                <td><span class="badge bg-{{ $r->new_status=='present'?'success':($r->new_status=='absent'?'danger':'info') }}">{{ ucfirst($r->new_status) }}</span></td>
                <td>{{ Str::limit($r->reason, 40) }}</td>
                <td><span class="badge bg-{{ $r->status=='approved'?'success':($r->status=='rejected'?'danger':'warning text-dark') }}">{{ ucfirst($r->status) }}</span></td>
                <td>{{ $r->created_at }}</td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="9" class="text-center text-muted py-3">No correction requests</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
