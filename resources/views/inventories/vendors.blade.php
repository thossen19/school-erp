@extends('layouts.app')
@section('title', 'Vendors')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-truck me-2"></i>Vendors</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item active">Vendors</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVendorModal"><i class="fas fa-plus me-1"></i>Add Vendor</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Vendor Name','Contact Person','Phone','Email','Category','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Office Supplies Inc.','Furniture Mart','Tech Solutions','Sports World','Cleaning Co.','Book Distributors'][$i-1] }}</td>
                <td>{{ ['John Vendor','Sarah Vendor','Mike Vendor','Emma Vendor','Tom Vendor','Lisa Vendor'][$i-1] }}</td>
                <td>+1-555-{{ sprintf('%04d',$i+9000) }}</td>
                <td><small>{{ strtolower(str_replace(' ','','vendor'.$i)).'@email.com' }}</small></td>
                <td>{{ ['Stationery','Furniture','Electronics','Sports','Cleaning','Books'][$i-1] }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addVendorModal" title="Add Vendor">
    <form>
        <x-form-input name="name" label="Vendor Name" required />
        <x-form-input name="contact_person" label="Contact Person" />
        <x-form-input name="phone" label="Phone" />
        <x-form-input name="email" label="Email" type="email" />
        <x-form-select name="category" label="Category" :options="['stationery'=>'Stationery','furniture'=>'Furniture','electronics'=>'Electronics','sports'=>'Sports','books'=>'Books','other'=>'Other']" />
        <x-form-textarea name="address" label="Address" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection