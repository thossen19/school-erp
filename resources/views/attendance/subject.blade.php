@extends('layouts.app')
@section('title', 'Subject Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Subject Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Subject Attendance</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                </select>
            </div>
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
        <x-table :headers="['#','Student','Class','Section','Date','Subject','Status','Remarks']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->section_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td>{{ $r->subject_id }}</td>
                <td><span class="badge bg-{{ $r->status=='present'?'success':($r->status=='absent'?'danger':($r->status=='late'?'warning text-dark':'info')) }}">{{ ucfirst($r->status) }}</span></td>
                <td>{{ $r->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="8" class="text-center text-muted py-3">No subject attendance records</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
