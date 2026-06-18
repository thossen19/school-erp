@extends('layouts.app')
@section('title', 'Student ID Generation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-id-card me-2"></i>Student ID Generation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">ID Generation</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Admission No','Name','Class','Status','Actions']">
            @forelse($students as $s)
            <tr>
                <td>{{ $loop->iteration + ($students->currentPage()-1)*$students->perPage() }}</td>
                <td><small class="text-muted">{{ $s->admission_no ?? '-' }}</small></td>
                <td class="fw-semibold">{{ $s->first_name }} {{ $s->last_name ?? '' }}</td>
                <td>{{ $s->class_name ?? '-' }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <div class="table-actions">
                        <form method="POST" action="{{ route('admissions.student-id-generation.generate', $s->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-primary"><i class="fas fa-id-card me-1"></i>Generate ID</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No students found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$students" />
@endsection
