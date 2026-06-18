@extends('layouts.app')
@section('title', 'Attendance Prediction')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Attendance Prediction</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Attendance Prediction</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <x-stats-card icon="fa-percentage" value="{{ number_format($stats->avg_score ?? 0, 1) }}%" title="Average Attendance Prediction" color="info" />
    </div>
    <div class="col-md-3">
        <x-stats-card icon="fa-calculator" value="{{ $stats->total ?? 0 }}" title="Total Predictions" color="primary" />
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#attSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Filter by Class <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="attSearchCollapse">
            <form method="GET" action="{{ route('ai.attendance-prediction') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button>
                    <a href="{{ route('ai.attendance-prediction') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No','Prediction Score','Label','Predicted At']">
            @forelse($predictions as $p)
            <tr>
                <td>{{ $loop->iteration + ($predictions->currentPage()-1)*$predictions->perPage() }}</td>
                <td class="fw-semibold">{{ trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) ?: '-' }}</td>
                <td>{{ $p->admission_no ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ ($p->prediction_score ?? 0) >= 70 ? 'success' : (($p->prediction_score ?? 0) >= 40 ? 'warning' : 'danger') }}">
                        {{ $p->prediction_score ?? 0 }}%
                    </span>
                </td>
                <td>{{ $p->prediction_label ?? '-' }}</td>
                <td>{{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('M d, Y H:i') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No predictions found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$predictions" />
@endsection
