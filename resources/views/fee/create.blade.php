@extends('layouts.app')
@section('title', 'Add Fee Structure')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Fee Structure</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Add Fee Structure</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-money-bill me-2 text-primary"></i>Fee Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="fee_head" label="Fee Head" required placeholder="e.g. Tuition Fee" /></div>
                <div class="col-md-4"><x-form-select name="class" label="Class" required :options="['1'=>'Grade 1','2'=>'Grade 2','3'=>'Grade 3','4'=>'Grade 4','5'=>'Grade 5','6'=>'Grade 6','7'=>'Grade 7','8'=>'Grade 8','9'=>'Grade 9','10'=>'Grade 10','11'=>'Grade 11','12'=>'Grade 12','all'=>'All Classes']" /></div>
                <div class="col-md-4"><x-form-select name="academic_year" label="Academic Year" :options="['2026'=>'2025-2026']" /></div>
                <div class="col-md-3"><x-form-input name="amount" label="Amount ($)" type="number" step="0.01" required /></div>
                <div class="col-md-3"><x-form-select name="frequency" label="Frequency" :options="['monthly'=>'Monthly','quarterly'=>'Quarterly','yearly'=>'Yearly','one_time'=>'One Time']" /></div>
                <div class="col-md-3"><x-form-input name="due_day" label="Due Day of Month" type="number" value="15" /></div>
                <div class="col-md-3"><x-form-input name="late_fee" label="Late Fee ($)" type="number" step="0.01" value="25" /></div>
                <div class="col-12"><x-form-textarea name="description" label="Description" rows="2" /></div>
                <div class="col-12">
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_active" checked><label class="form-check-label" for="is_active">Active</label></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_mandatory" checked><label class="form-check-label" for="is_mandatory">Mandatory</label></div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Fee Structure</button>
        <a href="{{ route('fees.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection