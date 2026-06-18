@extends('layouts.app')
@section('title', 'Teacher Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Teacher Allocation</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="teacher_id" class="form-select form-select-sm"><option value="">All Teachers</option>
                    @foreach($teachers as $t)<option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->first_name }} {{ $t->last_name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="day" class="form-select form-select-sm">
                    <option value="">All Days</option>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)
                    <option value="{{ $d }}" {{ request('day')==$d?'selected':'' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Timetable','Class/Section','Subject','Day','Start','End','Teacher']">
            @foreach($periods as $p)
            <tr>
                <td>{{ $p->timetable_name ?? '-' }}</td>
                <td>{{ $p->class_name ?? '-' }} {{ $p->section_name ? '/ '.$p->section_name : '' }}</td>
                <td>{{ $p->subject_name ?? $p->subject_id ?? '-' }}</td>
                <td>{{ $p->day_of_week }}</td>
                <td>{{ $p->start_time }}</td>
                <td>{{ $p->end_time }}</td>
                <td>{{ $p->teacher_id }}</td>
            </tr>
            @endforeach
            @if($periods->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No teacher allocations found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$periods" />
</div>
@endsection
