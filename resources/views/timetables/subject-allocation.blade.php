@extends('layouts.app')
@section('title', 'Subject Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Subject Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Subject Allocation</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="subject_id" class="form-select form-select-sm"><option value="">All Subjects</option>
                    @foreach($subjects as $s)<option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
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
        <x-table :headers="['Timetable','Class','Subject','Day','Start','End','Period #']">
            @foreach($periods as $p)
            <tr>
                <td>{{ $p->timetable_name ?? '-' }}</td>
                <td>{{ $p->class_name ?? '-' }}</td>
                <td class="fw-semibold">{{ $p->subject_name ?? $p->subject_id ?? '-' }}</td>
                <td>{{ $p->day_of_week }}</td>
                <td>{{ $p->start_time }}</td>
                <td>{{ $p->end_time }}</td>
                <td>{{ $p->period_number ?? '-' }}</td>
            </tr>
            @endforeach
            @if($periods->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No subject allocations found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$periods" />
</div>
@endsection
