@extends("layouts.app")

@section("title", "Departments")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-building me-2"></i>Departments</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Departments</li></ol></nav>
    </div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Employees','Status']">
            @forelse($departments as $d)
                <tr>
                    <td class="fw-semibold">{{ $d->name }}</td>
                    <td>{{ $d->code }}</td>
                    <td>{{ $d->employees_count }}</td>
                    <td><span class="badge bg-{{ $d->status ? 'success' : 'danger' }}">{{ $d->status ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$departments" />
</div>
@endsection
