@extends('layouts.app')
@section('title', 'Edit Book')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Book</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item"><a href="{{ route('library.show',1) }}">To Kill a Mockingbird</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="isbn" label="ISBN" value="978-0-06-112008-4" /></div>
                <div class="col-md-4"><x-form-input name="title" label="Title" value="To Kill a Mockingbird" /></div>
                <div class="col-md-4"><x-form-input name="author" label="Author" value="Harper Lee" /></div>
                <div class="col-md-4"><x-form-input name="publisher" label="Publisher" value="J.B. Lippincott & Co." /></div>
                <div class="col-md-4"><x-form-input name="year" label="Year" type="number" value="1960" /></div>
                <div class="col-md-4"><x-form-select name="category" label="Category" :options="['fiction'=>'Fiction']" value="fiction" /></div>
                <div class="col-md-3"><x-form-input name="total_copies" label="Total Copies" type="number" value="5" /></div>
                <div class="col-md-3"><x-form-input name="available_copies" label="Available Copies" type="number" value="3" /></div>
                <div class="col-md-3"><x-form-input name="rack" label="Rack" value="A-1" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('library.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection