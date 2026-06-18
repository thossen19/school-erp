@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Subjects</h1>
    <a href="{{ route('subjects.create') }}" class="btn btn-primary">Add New</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Classes</th>
                    <th>Teachers</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->code }}</td>
                        <td>{{ ucfirst($subject->type) }}</td>
                        <td>{{ $subject->classes_count ?? 0 }}</td>
                        <td>{{ $subject->teachers_count ?? 0 }}</td>
                        <td>
                            @if($subject->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$subjects" />
@endsection
