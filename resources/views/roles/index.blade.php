@extends('layouts.app')
@section('title', 'Roles & Permissions')
@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0"><i class="fas fa-shield-alt me-2"></i>Roles & Permissions</h4>
            <p class="text-muted mb-0">Manage user roles and their access permissions</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>New Role</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    @foreach($roles as $role)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h5>
                        <small class="text-muted">{{ $role->permissions->count() }} permission(s)</small>
                    </div>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $role->name }}</span>
                </div>
                @if($role->permissions->isNotEmpty())
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach($role->permissions->take(6) as $perm)
                            <span class="badge bg-light text-dark small">{{ explode('.', $perm->name)[0] }}</span>
                        @endforeach
                        @if($role->permissions->count() > 6)
                            <span class="badge bg-light text-muted small">+{{ $role->permissions->count() - 6 }} more</span>
                        @endif
                    </div>
                @else
                    <p class="text-muted small mb-3">No permissions assigned</p>
                @endif
                <div class="d-flex gap-2">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i>Edit</a>
                    @if($role->name !== 'super_admin')
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
