@extends('layouts.app')
@section('title', 'Academic Years')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar me-2"></i>Academic Years</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Academic Years</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ayModal"><i class="fas fa-plus me-1"></i>Add Year</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Start Date','End Date','Current']">
            @foreach($academicYears as $ay)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $ay->name }}</td>
                <td>{{ $ay->start_date }}</td>
                <td>{{ $ay->end_date }}</td>
                <td><span class="badge bg-{{ $ay->is_current ? 'success' : 'secondary' }}">{{ $ay->is_current ? 'Current' : 'Inactive' }}</span></td>
            </tr>
            @endforeach
            @if($academicYears->isEmpty())
            <tr><td colspan="5" class="text-center text-muted py-4">No academic years</td></tr>
            @endif
        </x-table>
    </div>
</div>

<x-modal id="ayModal" title="Add Academic Year">
    <form method="POST" action="{{ route('academic.academic-years.store') }}">
        @csrf
        <x-form-input name="name" label="Name" placeholder="e.g. 2026-2027" required />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="start_date" label="Start Date" type="date" required /></div>
            <div class="col-md-6"><x-form-input name="end_date" label="End Date" type="date" required /></div>
        </div>
        <div class="form-check"><input type="checkbox" class="form-check-input" name="is_current" value="1"><label class="form-check-label small">Set as current year</label></div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#ayModal form').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
