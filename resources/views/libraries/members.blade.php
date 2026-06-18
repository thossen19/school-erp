@extends('layouts.app')
@section('title', 'Library Members')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-id-card me-2"></i>Library Members</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item active">Members</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal"><i class="fas fa-plus me-1"></i>Add Member</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Member ID','Name','Type','Class/Dept','Phone','Books Issued','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td>LIB-{{ sprintf('%04d',$i) }}</td>
                <td class="fw-semibold">{{ ['John Doe','Jane Smith','Robert Brown','Emily Clark','David Wilson','Sarah Lee','Michael Hall','Laura Adams'][$i-1] }}</td>
                <td>{{ $i%2==0?'Teacher':'Student' }}</td>
                <td>{{ $i%2==0?'Math Dept':'Grade 10A' }}</td>
                <td>+1-555-{{ sprintf('%04d',$i+10000) }}</td>
                <td>{{ rand(0,5) }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addMemberModal" title="Add Library Member">
    <form>
        <x-form-select name="type" label="Member Type" :options="['student'=>'Student','teacher'=>'Teacher','staff'=>'Staff']" />
        <x-form-select name="user" label="Select Person" :options="['1'=>'John Doe','2'=>'Jane Smith']" />
        <x-form-input name="membership_date" label="Membership Date" type="date" value="{{ date('Y-m-d') }}" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Add Member</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection