@extends('layouts.app')
@section('title', 'Mark Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-pen me-2"></i>Mark Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Mark Attendance</li></ol></nav>
    </div>
</div>

<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3"><label class="form-label fw-semibold small">Date</label><input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>Grade 10</option><option>Grade 9</option><option>Grade 8</option></select></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Section</label><select class="form-select form-select-sm"><option>A</option><option>B</option><option>C</option></select></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Subject</label><select class="form-select form-select-sm"><option>Mathematics</option><option>English</option><option>Science</option></select></div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-success btn-sm" onclick="setAll('present')"><i class="fas fa-check"></i> All Present</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="setAll('absent')"><i class="fas fa-times"></i> All Absent</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="setAll('late')"><i class="fas fa-clock"></i> All Late</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="setAll('leave')"><i class="fas fa-calendar"></i> All Leave</button>
                </div>
            </div>
            <x-table :headers="['#','Student ID','Name','Roll No.','Present','Absent','Late','Leave','Remarks']">
                @foreach(range(1,12) as $i)
                <tr>
                    <td>{{ $i }}</td>
                    <td>STU-{{ sprintf('%04d',$i+50) }}</td>
                    <td class="fw-semibold">Student {{ $i }}</td>
                    <td>{{ $i }}</td>
                    <td><input type="radio" name="attendance[{{ $i }}]" value="present" class="form-check-input att-radio att-present" checked></td>
                    <td><input type="radio" name="attendance[{{ $i }}]" value="absent" class="form-check-input att-radio att-absent"></td>
                    <td><input type="radio" name="attendance[{{ $i }}]" value="late" class="form-check-input att-radio att-late"></td>
                    <td><input type="radio" name="attendance[{{ $i }}]" value="leave" class="form-check-input att-radio att-leave"></td>
                    <td><input type="text" class="form-control form-control-sm" placeholder="Optional" style="min-width:100px"></td>
                </tr>
                @endforeach
            </x-table>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Attendance</button>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
@push('scripts')
<script>
function setAll(val) {
    document.querySelectorAll('.att-radio').forEach(r => { if(r.value === val) r.checked = true; });
}
</script>
@endpush