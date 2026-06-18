@extends('layouts.app')

@section('title', 'Sections')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Sections</h1>
    <a href="{{ route('sections.create') }}" class="btn btn-primary">Add New</a>
</div>

@if($classes->count())
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
</form>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                    <tr>
                        <td>{{ $section->name }}</td>
                        <td>{{ $section->class->name ?? '-' }}</td>
                        <td>{{ $section->capacity ?? '-' }}</td>
                        <td>
                            @if($section->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$sections" />
@endsection
