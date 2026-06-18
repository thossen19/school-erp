@extends('layouts.app')
@section('title', 'Add Book')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Book</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item active">Add Book</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="isbn" label="ISBN" required /></div>
                <div class="col-md-4"><x-form-input name="title" label="Book Title" required /></div>
                <div class="col-md-4"><x-form-input name="author" label="Author" required /></div>
                <div class="col-md-4"><x-form-input name="publisher" label="Publisher" /></div>
                <div class="col-md-4"><x-form-input name="year" label="Publication Year" type="number" /></div>
                <div class="col-md-4"><x-form-select name="category" label="Category" :options="['fiction'=>'Fiction','non_fiction'=>'Non-Fiction','academic'=>'Academic','reference'=>'Reference','magazine'=>'Magazine','other'=>'Other']" /></div>
                <div class="col-md-4"><x-form-input name="total_copies" label="Total Copies" type="number" value="1" /></div>
                <div class="col-md-4"><x-form-input name="available_copies" label="Available Copies" type="number" value="1" /></div>
                <div class="col-md-4"><x-form-input name="rack" label="Rack Location" placeholder="e.g. A-1" /></div>
                <div class="col-12"><x-form-textarea name="description" label="Description" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Book</button>
        <a href="{{ route('library.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection