@extends('layouts.app')
@section('title', 'Health Checkups')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-stethoscope me-2"></i>Health Checkups</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Health Checkups</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Student</label>
                <select name="student_id" class="form-select form-select-sm">
                    <option value="">All Students</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">From Date</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">To Date</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('student_id') || request('date_from') || request('date_to'))<a href="{{ route('health.checkups') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Student','Admission No','Height','Weight','BMI','Blood Pressure','Vision','Dental','Conducted By']">
            @forelse($records as $r)
            <tr>
                <td class="fw-semibold">{{ \Carbon\Carbon::parse($r->checkup_date)->format('d-m-Y') }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->height ? $r->height.' cm' : '-' }}</td>
                <td>{{ $r->weight ? $r->weight.' kg' : '-' }}</td>
                <td>{{ $r->bmi ? number_format($r->bmi, 1) : '-' }}</td>
                <td>{{ $r->blood_pressure ?? '-' }}</td>
                <td><small>{{ $r->vision_left ?? '-' }} / {{ $r->vision_right ?? '-' }}</small></td>
                <td>{{ $r->dental_health ?? '-' }}</td>
                <td>{{ $r->conducted_by ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center py-4 text-muted">No checkup records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />
@endsection
