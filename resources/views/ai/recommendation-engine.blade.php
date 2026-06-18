@extends('layouts.app')
@section('title', 'AI Recommendation Engine')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-lightbulb me-2"></i>AI Recommendation Engine</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Recommendation Engine</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recommendationModal"><i class="fas fa-plus me-1"></i>Add Recommendation</button>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#recSearchCollapse" role="button">
            <i class="fas fa-filter me-1"></i>Filter by Type <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="recSearchCollapse">
            <form method="GET" action="{{ route('ai.recommendation-engine') }}" class="row g-2">
                <div class="col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="student" {{ request('type')=='student'?'selected':'' }}>Student</option>
                        <option value="course" {{ request('type')=='course'?'selected':'' }}>Course</option>
                        <option value="activity" {{ request('type')=='activity'?'selected':'' }}>Activity</option>
                    </select>
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button>
                    <a href="{{ route('ai.recommendation-engine') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Type','Recommendation','Confidence','Status','Created At']">
            @forelse($recommendations as $r)
            <tr>
                <td>{{ $loop->iteration + ($recommendations->currentPage()-1)*$recommendations->perPage() }}</td>
                <td><span class="badge bg-{{ $r->type == 'student' ? 'primary' : ($r->type == 'course' ? 'success' : 'info') }}">{{ ucfirst($r->type ?? 'N/A') }}</span></td>
                <td>{{ \Str::limit($r->recommendation ?? $r->message ?? '-', 60) }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar bg-{{ ($r->confidence ?? 0) >= 70 ? 'success' : (($r->confidence ?? 0) >= 40 ? 'warning' : 'danger') }}" style="width: {{ $r->confidence ?? 0 }}%"></div>
                        </div>
                        <small class="fw-bold">{{ $r->confidence ?? 0 }}%</small>
                    </div>
                </td>
                <td>
                    @php
                        $st = $r->status ?? 'active';
                        $stc = $st == 'active' ? 'success' : 'secondary';
                    @endphp
                    <span class="badge bg-{{ $stc }}">{{ ucfirst($st) }}</span>
                </td>
                <td>{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('M d, Y H:i') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No recommendations found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$recommendations" />

<x-modal id="recommendationModal" title="Add Recommendation">
    <form method="POST" action="{{ route('ai.recommendation-engine.store') }}" id="recommendationForm">
        @csrf
        <div class="row g-2">
            <div class="col-md-12">
                <x-form-select name="type" label="Type" :options="['student' => 'Student', 'course' => 'Course', 'activity' => 'Activity']" required />
            </div>
            <div class="col-md-12"><x-form-textarea name="recommendation" label="Recommendation" rows="4" required placeholder="Enter recommendation details" /></div>
            <div class="col-md-6"><x-form-input name="confidence" label="Confidence (0-100)" type="number" min="0" max="100" required placeholder="e.g. 85" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" type="submit" form="recommendationForm"><i class="fas fa-save me-1"></i>Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
