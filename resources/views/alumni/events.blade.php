@extends('layouts.app')
@section('title', 'Alumni Events')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-alt me-2"></i>Alumni Events</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Events</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Event</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Event title..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('status')!=='')<a href="{{ route('alumni.events') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Date','Venue','Status','Actions']">
            @forelse($events as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td class="fw-semibold">{{ $e->title }}</td>
                <td>{{ $e->date }}</td>
                <td>{{ $e->venue ?? '-' }}</td>
                <td>@if($e->status)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $e->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('alumni.events.delete', $e->id) }}" class="d-inline" onsubmit="return confirm('Delete this event?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No events found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$events" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.events.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Date</label><input type="date" name="date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Venue</label><input type="text" name="venue" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($events as $e)
<div class="modal fade" id="editModal{{ $e->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.events.update', $e->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Title</label><input type="text" name="title" class="form-control" value="{{ $e->title }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Date</label><input type="date" name="date" class="form-control" value="{{ $e->date }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Venue</label><input type="text" name="venue" class="form-control" value="{{ $e->venue }}"></div>
                    <div class="col-md-6"><div class="form-check mt-4"><input type="checkbox" name="status" class="form-check-input" value="1" id="es{{ $e->id }}" {{ $e->status ? 'checked' : '' }}><label class="form-check-label" for="es{{ $e->id }}">Active</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="3">{{ $e->description }}</textarea></div>
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