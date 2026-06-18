@extends('layouts.app')
@section('title', 'Late Entry Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Late Entry Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Late Entry Tracking</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $records->total() }}</h5><small class="text-muted">Late Entries</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $threshold }} min</h5><small class="text-muted">Late Threshold</small></div></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}"></div>
            <div class="col-auto">
                <select name="class_id" class="form-select form-select-sm">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)<option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Code','Class','Section','Date','Time In','Time Out','Status','Remarks']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->section_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td>{{ $r->time_in ? \Carbon\Carbon::parse($r->time_in)->format('H:i') : '-' }}</td>
                <td>{{ $r->time_out ? \Carbon\Carbon::parse($r->time_out)->format('H:i') : '-' }}</td>
                <td><span class="badge bg-warning text-dark">Late</span></td>
                <td>{{ $r->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="10" class="text-center text-muted py-3">No late entries found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
