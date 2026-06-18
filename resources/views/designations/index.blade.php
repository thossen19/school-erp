@extends("layouts.app")

@section("title", "Designations")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tag me-2"></i>Designations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Designations</li></ol></nav>
    </div>
    <a href="{{ route('designations.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Employees','Status']">
            @forelse($designations as $d)
                <tr>
                    <td class="fw-semibold">{{ $d->name }}</td>
                    <td>{{ $d->employees_count }}</td>
                    <td><span class="badge bg-{{ $d->status ? 'success' : 'danger' }}">{{ $d->status ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted py-3">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$designations" />
</div>
@endsection
