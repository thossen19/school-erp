@extends("layouts.app")

@section("title", "Add Room")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Add Room</h1>
        <a href="{{ route('timetable.room-allocation') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('timetable.room-allocation.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label for="code" class="form-label">Room Code <span class="text-danger">*</span></label>
                        <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                        <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" min="1" required>
                        @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="type" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="classroom" {{ old('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                            <option value="laboratory" {{ old('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                            <option value="library" {{ old('type') == 'library' ? 'selected' : '' }}>Library</option>
                            <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Office</option>
                            <option value="auditorium" {{ old('type') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                            <option value="sports" {{ old('type') == 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="music_room" {{ old('type') == 'music_room' ? 'selected' : '' }}>Music Room</option>
                            <option value="art_room" {{ old('type') == 'art_room' ? 'selected' : '' }}>Art Room</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="building" class="form-label">Building</label>
                        <input type="text" id="building" name="building" class="form-control @error('building') is-invalid @enderror" value="{{ old('building') }}">
                        @error('building')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="floor" class="form-label">Floor</label>
                        <input type="text" id="floor" name="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor') }}">
                        @error('floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" id="status" name="status" class="form-check-input" value="1" {{ old('status', true) ? 'checked' : '' }}>
                    <label for="status" class="form-check-label">Available</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Room</button>
                    <a href="{{ route('timetable.room-allocation') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection