@extends('layouts.app')
@section('title', 'Create Lesson Plan')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Create Lesson Plan</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ url()->previous() }}">Academic</a></li><li class="breadcrumb-item active">Create Lesson Plan</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Lesson Plan Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><x-form-input name="title" label="Lesson Title" required placeholder="e.g. Introduction to Algebra" /></div>
                <div class="col-md-3"><x-form-select name="class" label="Class" required :options="['1'=>'Grade 1','2'=>'Grade 2','3'=>'Grade 3','4'=>'Grade 4','5'=>'Grade 5','6'=>'Grade 6','7'=>'Grade 7','8'=>'Grade 8','9'=>'Grade 9','10'=>'Grade 10','11'=>'Grade 11','12'=>'Grade 12']" /></div>
                <div class="col-md-3"><x-form-select name="subject" label="Subject" required :options="['math'=>'Mathematics','english'=>'English','science'=>'Science','history'=>'History','physics'=>'Physics','chemistry'=>'Chemistry','biology'=>'Biology','computer'=>'Computer']" /></div>
                <div class="col-md-4"><x-form-input name="duration" label="Duration (minutes)" type="number" value="45" /></div>
                <div class="col-md-4"><x-form-input name="date" label="Date" type="date" value="{{ date('Y-m-d') }}" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published','scheduled'=>'Scheduled']" /></div>
                <div class="col-12"><x-form-textarea name="objectives" label="Learning Objectives" rows="3" placeholder="What students will learn..." /></div>
                <div class="col-12"><x-form-textarea name="materials" label="Materials Needed" rows="2" placeholder="Books, equipment, etc." /></div>
                <div class="col-12"><x-form-textarea name="procedure" label="Lesson Procedure" rows="5" placeholder="Step by step lesson plan..." /></div>
                <div class="col-12"><x-form-textarea name="assessment" label="Assessment / Homework" rows="2" placeholder="How will learning be assessed?" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Lesson Plan</button>
        <a href="{{ route_if_exists('lesson-plans.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection