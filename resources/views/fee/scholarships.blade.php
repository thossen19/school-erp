@extends('layouts.app')
@section('title', 'Scholarships')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-award me-2"></i>Scholarships</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Scholarships</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScholarModal"><i class="fas fa-plus me-1"></i>Add Scholarship</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Scholarship Name','Provider','Amount','Students Covered','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Merit Scholarship','Need Based','Sports Excellence','Academic Excellence','Music & Arts'][$i-1] }}</td>
                <td>{{ ['School Board','Alumni Fund','Sports Council','Academic Board','Arts Council'][$i-1] }}</td>
                <td>${{ number_format(rand(500,5000),2) }}</td>
                <td>{{ rand(5,30) }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Active','Pending','Expired'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addScholarModal" title="Add Scholarship">
    <form>
        <x-form-input name="name" label="Scholarship Name" required />
        <x-form-input name="provider" label="Provider" />
        <x-form-input name="amount" label="Amount ($)" type="number" step="0.01" />
        <x-form-select name="type" label="Type" :options="['merit'=>'Merit Based','need'=>'Need Based','sports'=>'Sports','academic'=>'Academic','other'=>'Other']" />
        <x-form-textarea name="criteria" label="Eligibility Criteria" rows="3" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection