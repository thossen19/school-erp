@extends('layouts.app')
@section('title', 'Edit Lesson Plan')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Lesson Plan</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('lesson-plans.index') }}">Academic</a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('lesson-plans.show',1) }}">Lesson Plan</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><x-form-input name="title" label="Title" value="Introduction to Algebra" /></div>
                <div class="col-md-3"><x-form-select name="class" label="Class" :options="['10'=>'Grade 10']" value="10" /></div>
                <div class="col-md-3"><x-form-select name="subject" label="Subject" :options="['math'=>'Mathematics']" value="math" /></div>
                <div class="col-md-4"><x-form-input name="duration" label="Duration (min)" type="number" value="45" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published']" value="published" /></div>
                <div class="col-12"><x-form-textarea name="objectives" label="Objectives" rows="2">Students will understand basic algebraic concepts</x-form-textarea></div>
                <div class="col-12"><x-form-textarea name="procedure" label="Procedure" rows="4">1. Warm-up 2. Introduction 3. Practice</x-form-textarea></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route_if_exists('lesson-plans.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection