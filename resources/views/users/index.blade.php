@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0"><i class="fas fa-users me-2"></i>Users</h4>
            <p class="text-muted mb-0">Manage all system users and their roles</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>New User</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#searchPanel" role="button" style="cursor:pointer">
        <small class="fw-semibold text-muted"><i class="fas fa-search me-1"></i>Advanced Search</small>
        <i class="fas fa-chevron-down text-muted small"></i>
    </div>
    <div class="collapse {{ request()->anyFilled(['search','role','date_from','date_to']) ? 'show' : '' }}" id="searchPanel">
        <div class="card-body pt-2">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $r)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From date">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To date">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100"><i class="fas fa-filter"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td><code class="small">{{ $user->email }}</code></td>
                        <td>
                            @php
                                $userRoles = DB::table('model_has_roles')->where('model_id', $user->id)->pluck('role_id');
                                $roleNames = DB::table('roles')->whereIn('id', $userRoles)->pluck('name');
                            @endphp
                            @foreach($roleNames as $rn)
                                <span class="badge bg-info bg-opacity-10 text-info me-1">{{ $rn }}</span>
                            @endforeach
                            @if($roleNames->isEmpty())
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                            @if($user->id !== 1)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$users" />
@endsection
