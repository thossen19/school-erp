@extends('layouts.app')
@section('title', 'Networking Platform')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-network-wired me-2"></i>Networking Platform</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Networking</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Profile</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name or industry..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Industry</label><select name="industry" class="form-select form-select-sm"><option value="">All</option>@foreach($industries as $ind)<option value="{{ $ind }}" {{ request('industry') == $ind ? 'selected' : '' }}>{{ $ind }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Mentor</label><select name="available_for_mentorship" class="form-select form-select-sm"><option value="">All</option><option value="1" {{ request('available_for_mentorship') === '1' ? 'selected' : '' }}>Available</option><option value="0" {{ request('available_for_mentorship') === '0' ? 'selected' : '' }}>Not Available</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('industry')||request('available_for_mentorship')!=='')<a href="{{ route('alumni.networking') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Alumnus','Batch','Occupation','Industry','Mentor','Skills','Actions']">
            @forelse($profiles as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td class="fw-semibold">{{ $p->first_name }} {{ $p->last_name }}<br><small class="text-muted">{{ $p->email }}</small></td>
                <td><span class="badge bg-secondary">{{ $p->graduation_year }}</span></td>
                <td><small>{{ $p->current_occupation ?? '-' }}@if($p->company) <br>{{ $p->company }}@endif</small></td>
                <td>{{ $p->industry ?? '-' }}</td>
                <td>@if($p->available_for_mentorship)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td><small>{{ Str::limit($p->skills, 30) ?? '-' }}</small></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('alumni.networking.delete', $p->id) }}" class="d-inline" onsubmit="return confirm('Delete this network profile?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No network profiles found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$profiles" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.networking.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Network Profile</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Alumnus</label><select name="alumni_id" class="form-select" required><option value="">Select</option>@foreach($alumniList as $al)<option value="{{ $al->id }}">{{ $al->first_name }} {{ $al->last_name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Industry</label><input type="text" name="industry" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Skills</label><input type="text" name="skills" class="form-control" placeholder="Comma separated"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Interests</label><input type="text" name="interests" class="form-control" placeholder="Comma separated"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Portfolio URL</label><input type="url" name="portfolio_url" class="form-control"></div>
                    <div class="col-12"><div class="form-check"><input type="checkbox" name="available_for_mentorship" class="form-check-input" value="1" id="nm"><label class="form-check-label" for="nm">Available for Mentorship</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Bio</label><textarea name="bio" class="form-control" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($profiles as $p)
<div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.networking.update', $p->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Network Profile</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Industry</label><input type="text" name="industry" class="form-control" value="{{ $p->industry }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Skills</label><input type="text" name="skills" class="form-control" value="{{ $p->skills }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Interests</label><input type="text" name="interests" class="form-control" value="{{ $p->interests }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control" value="{{ $p->linkedin_url }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Portfolio URL</label><input type="url" name="portfolio_url" class="form-control" value="{{ $p->portfolio_url }}"></div>
                    <div class="col-12"><div class="form-check"><input type="checkbox" name="available_for_mentorship" class="form-check-input" value="1" id="nem{{ $p->id }}" {{ $p->available_for_mentorship ? 'checked' : '' }}><label class="form-check-label" for="nem{{ $p->id }}">Available for Mentorship</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Bio</label><textarea name="bio" class="form-control" rows="3">{{ $p->bio }}</textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection