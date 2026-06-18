@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0"><i class="fas fa-edit me-2"></i>Edit Role: {{ ucfirst(str_replace('_', ' ', $role->name)) }}</h4>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf @method('PUT')
            @if($role->name === 'super_admin')
                <input type="hidden" name="name" value="super_admin">
                <div class="alert alert-warning">Super Admin role cannot be renamed or have permissions modified.</div>
            @else
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr>
            <h6 class="fw-semibold mb-3">Assign Permissions</h6>
            <div class="row g-3">
                @foreach($permissions as $group => $groupPerms)
                <div class="col-md-4">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-2 text-capitalize">{{ $group }}</h6>
                            @foreach($groupPerms as $perm)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-check-input" id="perm{{ $perm->id }}" {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="perm{{ $perm->id }}">{{ $perm->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="mt-4">
                @if($role->name !== 'super_admin')
                <button type="submit" class="btn btn-primary">Update Role</button>
                @else
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Back to Roles</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
