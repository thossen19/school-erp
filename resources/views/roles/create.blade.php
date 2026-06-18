@extends('layouts.app')
@section('title', 'Create Role')
@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2"></i>Create Role</h4>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. exam_controller">
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
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-check-input" id="perm{{ $perm->id }}" {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="perm{{ $perm->id }}">{{ $perm->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
