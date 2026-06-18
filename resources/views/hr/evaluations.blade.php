@extends('layouts.app')
@section('title', 'Employee Evaluations')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-star me-2"></i>Employee Evaluations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item active">Employee Evaluations</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Evaluation Type</label>
                <select name="evaluation_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($evaluationTypes as $t)
                    <option value="{{ $t }}" {{ request('evaluation_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Employee</label>
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">All Employees</option>
                    @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->first_name }} {{ $e->last_name }} ({{ $e->employee_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search comments..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['evaluation_type','status','employee_id','search']))
            <div class="col-12">
                <a href="{{ route('hr.evaluations') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
            </div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Rating</th>
                        <th>Evaluator</th>
                        <th>Status</th>
                        <th>Next Eval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $ev)
                    <tr>
                        <td>{{ $ev->id }}</td>
                        <td class="fw-semibold">{{ $ev->employee->full_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $ev->evaluation_type ?? 'General' }}</span></td>
                        <td>{{ $ev->evaluation_date }}</td>
                        <td>
                            @if($ev->rating)
                                <div class="d-flex align-items-center">
                                    <div class="me-2 fw-bold">{{ number_format($ev->rating, 1) }}</div>
                                    <div class="text-warning" style="font-size:0.8rem;">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="fas fa-star{{ $i <= round($ev->rating) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $ev->evaluator->full_name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClasses = ['pending' => 'warning', 'completed' => 'success', 'in-progress' => 'info'];
                                $statusClass = $statusClasses[$ev->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }}">
                                {{ ucfirst($ev->status) }}
                            </span>
                        </td>
                        <td>{{ $ev->next_evaluation_date ?? '-' }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="#" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No evaluations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$evaluations" />
@endsection
