@extends('layouts.app')
@section('title', 'Attendance Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Attendance Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Attendance Reports</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    @foreach($summary as $s)
    @php $colors=['present'=>'success','absent'=>'danger','late'=>'warning text-dark','half_day'=>'info']; @endphp
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-{{ explode(' ', $colors[$s->status] ?? 'primary')[0] }}">{{ $s->total }}</h5><small class="text-muted">{{ ucfirst($s->status) }}</small></div></div>
    @endforeach
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From"></div>
            <div class="col-auto"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To"></div>
            <div class="col-auto">
                <select name="class_id" class="form-select form-select-sm">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)<option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="present" {{ request('status')=='present'?'selected':'' }}>Present</option>
                    <option value="absent" {{ request('status')=='absent'?'selected':'' }}>Absent</option>
                    <option value="late" {{ request('status')=='late'?'selected':'' }}>Late</option>
                    <option value="half_day" {{ request('status')=='half_day'?'selected':'' }}>Half Day</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Generate</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Code','Class','Section','Date','Status','Attendance Type','Remarks']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->section_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td><span class="badge bg-{{ $r->status=='present'?'success':($r->status=='absent'?'danger':($r->status=='late'?'warning text-dark':'info')) }}">{{ ucfirst($r->status) }}</span></td>
                <td>{{ $r->attendance_type ?? '-' }}</td>
                <td>{{ $r->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="9" class="text-center text-muted py-3">No records</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
