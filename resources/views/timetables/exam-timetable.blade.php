@extends('layouts.app')
@section('title', 'Exam Timetable')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Exam Timetable</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Exam Timetable</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>
                    @foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Class','Section','Effective From','Effective To','Status','Created']">
            @foreach($timetables as $t)
            <tr>
                <td class="fw-semibold">{{ $t->name }}</td>
                <td>{{ $t->class_name ?? '-' }}</td>
                <td>{{ $t->section_name ?? 'N/A' }}</td>
                <td>{{ $t->effective_from ?? '-' }}</td>
                <td>{{ $t->effective_to ?? '-' }}</td>
                <td><span class="badge bg-{{ $t->is_active?'success':'secondary' }}">{{ $t->is_active?'Active':'Inactive' }}</span></td>
                <td>{{ $t->created_at }}</td>
            </tr>
            @endforeach
            @if($timetables->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No exam timetables found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$timetables" />
</div>
@endsection
