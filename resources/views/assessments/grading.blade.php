@extends('layouts.app')
@section('title', 'Grading System')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-layer-group me-2"></i>Grading System</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item active">Grading</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal"><i class="fas fa-plus me-1"></i>Add Grade Scale</button>
</div>

@forelse($gradingSystems as $system)
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0"><i class="fas fa-table me-2"></i>{{ $system->name }}
            @if($system->is_default)<span class="badge bg-info ms-2">Default</span>@endif
            <small class="text-muted ms-2">({{ $system->description }})</small>
        </h6>
        <button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i> Edit Scale</button>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['Grade','Description','Min Percentage','Max Percentage','Grade Point','Actions']">
            @forelse($system->gradeRanges as $g)
            <tr>
                <td><span class="fw-bold fs-5">{{ $g->grade }}</span></td>
                <td>{{ $g->description }}</td>
                <td>{{ $g->min_percentage }}%</td>
                <td>{{ $g->max_percentage }}%</td>
                <td>{{ $g->grade_point }}</td>
                <td><button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-3">No grade ranges defined.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
@empty
<div class="card shadow-sm border-0">
    <div class="card-body text-center text-muted py-5">
        <i class="fas fa-layer-group fa-3x mb-3"></i>
        <p>No grading systems found. Click "Add Grade Scale" to create one.</p>
    </div>
</div>
@endforelse

<x-modal id="addGradeModal" title="Add Grade Scale">
    <form>
        <x-form-input name="grade" label="Grade" required placeholder="e.g. A+" />
        <x-form-input name="description" label="Description" placeholder="e.g. Excellent" />
        <x-form-input name="min_percentage" label="Min Percentage" type="number" />
        <x-form-input name="max_percentage" label="Max Percentage" type="number" />
        <x-form-input name="grade_point" label="Grade Point" type="number" step="0.1" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
