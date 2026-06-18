@extends("layouts.app")

@section("title", "New Substitution Request")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">New Substitution Request</h1>
        <a href="{{ route('timetable.substitutions') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('timetable.substitutions.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="original_teacher_id" class="form-label">Original Teacher <span class="text-danger">*</span></label>
                        <select id="original_teacher_id" name="original_teacher_id" class="form-select @error('original_teacher_id') is-invalid @enderror" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('original_teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        @error('original_teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="substitute_teacher_id" class="form-label">Substitute Teacher</label>
                        <select id="substitute_teacher_id" name="substitute_teacher_id" class="form-select @error('substitute_teacher_id') is-invalid @enderror">
                            <option value="">Select Substitute</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('substitute_teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        @error('substitute_teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="timetable_period_id" class="form-label">Period ID</label>
                        <input type="number" id="timetable_period_id" name="timetable_period_id" class="form-control @error('timetable_period_id') is-invalid @enderror" value="{{ old('timetable_period_id') }}" placeholder="Period ID from timetable">
                        @error('timetable_period_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3">{{ old('reason') }}</textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="{{ route('timetable.substitutions') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection