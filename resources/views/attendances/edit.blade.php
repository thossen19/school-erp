@extends('layouts.app')
@section('title', 'Edit Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Edit Attendance - Grade 10A - Jun 13, 2026</h6></div>
        <div class="card-body">
            <x-table :headers="['#','Student','Present','Absent','Late','Leave','Remarks']">
                @foreach(range(1,10) as $i)
                <tr>
                    <td>{{ $i }}</td>
                    <td>Student {{ $i }}</td>
                    <td><input type="radio" name="att[{{$i}}]" value="present" class="form-check-input" {{ $i%4!=0?'checked':'' }}></td>
                    <td><input type="radio" name="att[{{$i}}]" value="absent" class="form-check-input" {{ $i%4==0?'checked':'' }}></td>
                    <td><input type="radio" name="att[{{$i}}]" value="late" class="form-check-input"></td>
                    <td><input type="radio" name="att[{{$i}}]" value="leave" class="form-check-input"></td>
                    <td><input type="text" class="form-control form-control-sm" value="{{ $i%4==0?'Sick':'' }}" style="min-width:100px"></td>
                </tr>
                @endforeach
            </x-table>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Attendance</button>
        <a href="{{ route('attendance.show', 1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection