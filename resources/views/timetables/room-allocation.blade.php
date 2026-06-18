@extends('layouts.app')
@section('title', 'Room Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-door-open me-2"></i>Room Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Room Allocation</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="classroom" {{ request('type')=='classroom'?'selected':'' }}>Classroom</option>
                    <option value="lab" {{ request('type')=='lab'?'selected':'' }}>Lab</option>
                    <option value="auditorium" {{ request('type')=='auditorium'?'selected':'' }}>Auditorium</option>
                    <option value="conference" {{ request('type')=='conference'?'selected':'' }}>Conference</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Type','Building','Floor','Capacity','Status']">
            @foreach($rooms as $r)
            <tr>
                <td class="fw-semibold">{{ $r->name }}</td>
                <td><code>{{ $r->code }}</code></td>
                <td>{{ ucfirst($r->type ?? 'N/A') }}</td>
                <td>{{ $r->building ?? '-' }}</td>
                <td>{{ $r->floor ?? '-' }}</td>
                <td>{{ $r->capacity }}</td>
                <td><span class="badge bg-{{ $r->status?'success':'danger' }}">{{ $r->status?'Available':'Unavailable' }}</span></td>
            </tr>
            @endforeach
            @if($rooms->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No rooms found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$rooms" />
</div>
@endsection
