@extends("layouts.app")

@section("title", "Employees")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-users me-2"></i>Employees</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Employees</li></ol></nav>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('employees.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Department</label>
                <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or employee no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Employee No','Name','Department','Designation']">
            @forelse($employees as $e)
                <tr>
                    <td>{{ $e->employee_no }}</td>
                    <td><a href="{{ route('employees.show', $e->id) }}" class="text-decoration-none fw-semibold">{{ $e->first_name }} {{ $e->last_name }}</a></td>
                    <td>{{ $e->department?->name ?? '-' }}</td>
                    <td>{{ $e->designation?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$employees" />
</div>
@endsection
