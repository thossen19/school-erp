@extends('layouts.app')
@section('title', 'Edit Inventory Item')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Item</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item"><a href="{{ route('inventory.show',1) }}">Notebooks</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="name" label="Item Name" value="Notebooks (A4 Ruled)" /></div>
                <div class="col-md-4"><x-form-input name="sku" label="SKU" value="SKU-0001" /></div>
                <div class="col-md-4"><x-form-select name="category" label="Category" :options="['stationery'=>'Stationery']" value="stationery" /></div>
                <div class="col-md-3"><x-form-input name="quantity" label="Quantity" type="number" value="150" /></div>
                <div class="col-md-3"><x-form-input name="unit" label="Unit" value="pcs" /></div>
                <div class="col-md-3"><x-form-input name="unit_price" label="Unit Price ($)" type="number" value="2.50" step="0.01" /></div>
                <div class="col-md-3"><x-form-input name="reorder_level" label="Reorder Level" type="number" value="10" /></div>
                <div class="col-12"><x-form-textarea name="description" label="Description" rows="2">A4 ruled notebooks</x-form-textarea></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('inventory.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection