@extends('layouts.app')
@section('title', 'Edit Timetable')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Timetable</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('timetable.index') }}">Timetable</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-table me-2"></i>Edit Schedule</h6></div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><x-form-select name="class" label="Class" :options="['10'=>'Grade 10']" value="10" /></div>
                <div class="col-md-4"><x-form-select name="section" label="Section" :options="['A'=>'A','B'=>'B']" value="A" /></div>
                <div class="col-md-4"><x-form-input name="period_duration" label="Duration (min)" type="number" value="45" /></div>
            </div>
            <x-table :headers="['Period','Time','Monday','Tuesday','Wednesday','Thursday','Friday']">
                @foreach(range(1,8) as $p)
                <tr>
                    <td>{{ $p }}</td>
                    <td class="small">{{ sprintf('%02d:00',7+$p) }}-{{ sprintf('%02d:45',7+$p) }}</td>
                    @foreach(range(1,5) as $d)
                    <td>
                        <select class="form-select form-select-sm" style="min-width:120px">
                            <option>Select Subject</option>
                            <option {{ $p==1?'selected':'' }}>Mathematics</option>
                            <option {{ $p==2?'selected':'' }}>English</option>
                            <option {{ $p==3?'selected':'' }}>Science</option>
                            <option>History</option>
                            <option>Art</option>
                            <option>PE</option>
                            <option>Computer</option>
                        </select>
                        <select class="form-select form-select-sm mt-1">
                            <option>Select Teacher</option>
                            <option>Mr. Johnson</option>
                            <option>Ms. Davis</option>
                            <option>Mrs. Smith</option>
                        </select>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </x-table>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Timetable</button>
        <a href="{{ route('timetable.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection