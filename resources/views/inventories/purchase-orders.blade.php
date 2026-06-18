@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-shopping-cart me-2"></i>Purchase Orders</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item active">Purchase Orders</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPOModal"><i class="fas fa-plus me-1"></i>New PO</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','PO No.','Vendor','Items','Total Amount','Order Date','Expected Date','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">PO-{{ sprintf('%04d',$i) }}</td>
                <td>{{ ['Office Supplies Inc.','Furniture Mart','Tech Solutions','Sports World','Cleaning Co.','Book Distributors'][$i-1] }}</td>
                <td>{{ rand(2,8) }}</td>
                <td class="fw-bold">${{ number_format(rand(500,10000),2) }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>Jun {{ $i+15 }}, 2026</td>
                <td><span class="badge bg-{{ ['success','warning','info','danger'][$i%4] }}">{{ ['Delivered','Pending','Processing','Cancelled'][$i%4] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addPOModal" title="New Purchase Order">
    <form>
        <x-form-select name="vendor" label="Vendor" :options="['1'=>'Office Supplies Inc.']" />
        <x-form-input name="order_date" label="Order Date" type="date" value="{{ date('Y-m-d') }}" />
        <x-form-input name="expected_date" label="Expected Delivery" type="date" />
        <x-form-textarea name="items" label="Items (one per line)" rows="3" placeholder="Item - Quantity - Price" />
        <x-form-textarea name="notes" label="Notes" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Create PO</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection