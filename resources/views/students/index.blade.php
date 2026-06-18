@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Students</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Students</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add New</a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-2">
        <a class="text-decoration-none fw-semibold small" data-bs-toggle="collapse" href="#studentSearchCollapse" role="button">
            <i class="fas fa-search me-1"></i>Advanced Search <i class="fas fa-chevron-down ms-1"></i>
        </a>
        <div class="collapse mt-2" id="studentSearchCollapse">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2">
                <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, admission no, roll no..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="class_id" class="form-select form-select-sm" id="filterClass"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="section_id" class="form-select form-select-sm" id="filterSection"><option value="">All Sections</option>@foreach($sections as $s)<option value="{{ $s->id }}" {{ request('section_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="gender" class="form-select form-select-sm"><option value="">All Genders</option><option value="male" {{ request('gender')=='male'?'selected':'' }}>Male</option><option value="female" {{ request('gender')=='female'?'selected':'' }}>Female</option></select></div>
                <div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">All Status</option><option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option><option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option><option value="transferred" {{ request('status')=='transferred'?'selected':'' }}>Transferred</option></select></div>
                <div class="col-md-1"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="DOB from"></div>
                <div class="col-md-1"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="DOB to"></div>
                <div class="col-md-12 mt-2"><button class="btn btn-sm btn-primary me-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a></div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Admission No', 'Name', 'Class', 'Section', 'Phone', 'Status', 'Actions']">
            @forelse($students as $student)
            <tr>
                <td>{{ $student->admission_no ?? '-' }}</td>
                <td>
                    <a href="{{ route('students.show', $student->id) }}" class="text-decoration-none fw-semibold">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </a>
                </td>
                <td>{{ $student->class->name ?? '-' }}</td>
                <td>{{ $student->section->name ?? '-' }}</td>
                <td>{{ $student->phone ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'danger' : 'warning') }}">
                        {{ ucfirst($student->status ?? 'active') }}
                    </span>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="d-inline" onsubmit="return confirm('Delete student {{ $student->first_name }} {{ $student->last_name }}? This action cannot be undone.');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No students found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>

<x-pagination :paginator="$students" />

@push('scripts')
<script>
var allSections = {!! json_encode(\App\Models\Academic\Section::where('school_id', $schoolId)->orderBy('name')->get(['id', 'name', 'class_id'])->toArray()) !!};
$('#filterClass').change(function() {
    var classId = $(this).val();
    var sel = $('#filterSection');
    sel.empty().append('<option value="">All Sections</option>');
    if (classId) {
        $.each(allSections, function(i, s) {
            if (s.class_id == classId) sel.append('<option value="' + s.id + '">' + s.name + '</option>');
        });
    }
});
</script>
@endpush
@endsection
