@extends('layouts.app')
@section('title', 'Grading System')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-layer-group me-2"></i>Grading System</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Grading System</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="System name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request('search'))
            <div class="col-12"><a href="{{ route('assessment.grading-system') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="row g-4">
    @forelse($gradingSystems as $gs)
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0">{{ $gs->name }} {!! $gs->is_default ? '<span class="badge bg-primary ms-1">Default</span>' : '' !!}</h6>
                <span class="badge bg-{{ $gs->status ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $gs->status ? 'success' : 'secondary' }}">{{ $gs->status ? 'Active' : 'Inactive' }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Grade</th><th>Min %</th><th>Max %</th><th>Grade Point</th></tr>
                    </thead>
                    <tbody>
                        @forelse($gs->gradeRanges as $gr)
                        <tr>
                            <td><span class="fw-bold">{{ $gr->grade }}</span></td>
                            <td>{{ $gr->min_percentage }}%</td>
                            <td>{{ $gr->max_percentage }}%</td>
                            <td>{{ $gr->grade_point }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-2 text-muted">No grade ranges defined</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0"><div class="card-body text-center py-5 text-muted"><i class="fas fa-layer-group fa-3x mb-3"></i><p class="mb-0">No grading systems found.</p></div></div>
    </div>
    @endforelse
</div>
<x-pagination :paginator="$gradingSystems" />
@endsection
