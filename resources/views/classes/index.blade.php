@extends('layouts.app')

@section('title', 'Classes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Classes</h1>
    <a href="{{ route('classes.create') }}" class="btn btn-primary">Add New</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Numeric Value</th>
                    <th>Sections</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td>
                            <a href="{{ route('classes.show', $class->id) }}">{{ $class->name }}</a>
                        </td>
                        <td>{{ $class->numeric_value ?? '-' }}</td>
                        <td>{{ $class->sections_count ?? 0 }}</td>
                        <td>{{ $class->students_count ?? 0 }}</td>
                        <td>
                            @if($class->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('classes.sections', $class->id) }}" class="btn btn-sm btn-outline-info">Sections</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
