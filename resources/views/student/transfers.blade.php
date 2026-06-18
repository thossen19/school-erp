@extends('layouts.app')
@section('title', 'Student Transfers')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Student Transfers</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Transfers</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferModal"><i class="fas fa-exchange-alt me-1"></i>New Transfer</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','From School','To School','Transfer Date','TC No.','Reason','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td>{{ ['Current School','Lincoln High','Washington School'][$i%3] }}</td>
                <td>{{ ['Lincoln High','Washington School','Riverside Academy'][$i%3] }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>TC-{{ sprintf('%04d',$i) }}</td>
                <td>{{ ['Family moved','School change','Relocation','Other','Course change'][$i-1] }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Completed','Pending','Processing'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="transferModal" title="New Transfer Application">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" required />
        <x-form-select name="type" label="Transfer Type" :options="['internal'=>'Internal (School)','external'=>'External (Outgoing)','incoming'=>'Incoming' ]" />
        <x-form-input name="transfer_date" label="Transfer Date" type="date" />
        <x-form-input name="to_school" label="To School Name" placeholder="School name" />
        <x-form-select name="reason" label="Reason" :options="['relocation'=>'Relocation','school_change'=>'School Change','family'=>'Family Reason','other'=>'Other']" />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Process Transfer</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection