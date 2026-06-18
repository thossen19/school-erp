@extends('layouts.app')
@section('title', 'Fee Discounts')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Fee Discounts</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Discounts</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiscountModal"><i class="fas fa-plus me-1"></i>Add Discount</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Discount Name','Type','Value','Student/Group','Valid Until','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ ['Sibling Discount','Merit Scholarship','Staff Child','Early Bird','Need Based','Sports Scholarship'][$i-1] }}</td>
                <td>{{ ['Percentage','Fixed','Percentage','Percentage','Fixed','Percentage'][$i-1] }}</td>
                <td>{{ ['10%','$500','25%','5%','$1000','15%'][$i-1] }}</td>
                <td>{{ ['All','Student 1','Staff','All','Student 3','All'][$i-1] }}</td>
                <td>Dec {{ $i }}, 2026</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addDiscountModal" title="Add Discount">
    <form>
        <x-form-input name="name" label="Discount Name" required />
        <x-form-select name="type" label="Discount Type" :options="['percentage'=>'Percentage','fixed'=>'Fixed Amount']" />
        <x-form-input name="value" label="Value" type="number" step="0.01" required />
        <x-form-select name="applicable_to" label="Applicable To" :options="['all'=>'All Students','individual'=>'Individual','group'=>'Group/Class']" />
        <x-form-input name="valid_until" label="Valid Until" type="date" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save Discount</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection