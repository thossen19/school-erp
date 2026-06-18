@extends('layouts.app')
@section('title', 'Add Inventory Item')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Inventory Item</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item active">Add Item</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="name" label="Item Name" required /></div>
                <div class="col-md-4"><x-form-input name="sku" label="SKU Code" required placeholder="SKU-0001" /></div>
                <div class="col-md-4"><x-form-select name="category" label="Category" :options="['stationery'=>'Stationery','furniture'=>'Furniture','electronics'=>'Electronics','cleaning'=>'Cleaning','sports'=>'Sports','other'=>'Other']" /></div>
                <div class="col-md-3"><x-form-input name="quantity" label="Quantity" type="number" value="0" /></div>
                <div class="col-md-3"><x-form-input name="unit" label="Unit" value="pcs" placeholder="pcs, kg, units" /></div>
                <div class="col-md-3"><x-form-input name="unit_price" label="Unit Price ($)" type="number" step="0.01" /></div>
                <div class="col-md-3"><x-form-input name="reorder_level" label="Reorder Level" type="number" value="10" /></div>
                <div class="col-md-4"><x-form-select name="vendor" label="Preferred Vendor" :options="['1'=>'Vendor 1','2'=>'Vendor 2']" /></div>
                <div class="col-md-4"><x-form-input name="location" label="Storage Location" placeholder="e.g. Store Room A" /></div>
                <div class="col-12"><x-form-textarea name="description" label="Description" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Item</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection