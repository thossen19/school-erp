@extends('layouts.app')
@section('title', 'Create Timetable')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Create Timetable</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('timetable.index') }}">Timetable</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-cog me-2 text-primary"></i>Schedule Settings</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="class" label="Class" required :options="['10'=>'Grade 10','9'=>'Grade 9']" /></div>
                <div class="col-md-4"><x-form-select name="section" label="Section" :options="['A'=>'A','B'=>'B','C'=>'C']" /></div>
                <div class="col-md-4"><x-form-select name="academic_year" label="Academic Year" :options="['2026'=>'2025-2026']" /></div>
                <div class="col-md-4"><x-form-input name="periods_per_day" label="Periods per Day" type="number" value="8" /></div>
                <div class="col-md-4"><x-form-input name="period_duration" label="Period Duration (min)" type="number" value="45" /></div>
                <div class="col-md-4"><x-form-input name="break_time" label="Break Duration (min)" type="number" value="15" /></div>
                <div class="col-12"><x-form-textarea name="notes" label="Notes" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create Timetable</button>
        <a href="{{ route('timetable.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection