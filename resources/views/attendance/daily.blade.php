@extends('layouts.app')
@section('title', 'Daily Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Daily Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Daily Attendance</li></ol></nav>
    </div>
    <div><a href="{{ route('attendance.mark') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Mark Attendance</a></div>
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
            <div class="col-auto">
                <select name="section_id" class="form-select form-select-sm">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)<option value="{{ $section->id }}" {{ request('section_id')==$section->id?'selected':'' }}>{{ $section->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Class','Section','Date','Status','Type','Remarks']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->section_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td><span class="badge bg-{{ $r->status=='present'?'success':($r->status=='absent'?'danger':($r->status=='late'?'warning text-dark':'info')) }}">{{ ucfirst($r->status) }}</span></td>
                <td>{{ $r->attendance_type ?? '-' }}</td>
                <td>{{ $r->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="8" class="text-center text-muted py-3">No records</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
