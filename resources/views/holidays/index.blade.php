@extends("layouts.app")

@section("title", "Holidays")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Holidays</h1>
        <a href="{{ route('holidays.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <select name="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @foreach(range(now()->year - 1, now()->year + 2) as $y)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="national" {{ request('type') == 'national' ? 'selected' : '' }}>National</option>
                        <option value="festival" {{ request('type') == 'festival' ? 'selected' : '' }}>Festival</option>
                        <option value="religious" {{ request('type') == 'religious' ? 'selected' : '' }}>Religious</option>
                        <option value="school" {{ request('type') == 'school' ? 'selected' : '' }}>School</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('holidays.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->date?->format('d-m-Y') ?? $holiday->date }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($holiday->type) }}</span></td>
                                <td>
                                    @if($holiday->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No holidays found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$holidays" />
    </div>
</div>
@endsection
