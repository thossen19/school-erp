@extends('layouts.app')
@section('title', 'Leave Requests')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-signature me-2"></i>Leave Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Leave Requests</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newLeaveModal"><i class="fas fa-plus me-1"></i>New Leave Request</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student/Staff','Type','From','To','Days','Reason','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">{{ ['John Doe','Ms. Sarah Wilson','Alice Smith','Mr. Robert Brown','Emily Clark','Dr. David Lee','Grace Hall','Henry King'][$i-1] }}</a></td>
                <td>{{ $i%2==0?'Staff':'Student' }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>Jun {{ $i+2 }}, 2026</td>
                <td>3</td>
                <td>{{ ['Sick leave','Family event','Medical appointment','Personal','Vacation','Emergency','Medical','Personal'][$i-1] }}</td>
                <td>
                    @php $lst = ['pending','approved','rejected']; $ls = $lst[array_rand($lst)]; @endphp
                    <span class="badge bg-{{ $ls=='approved'?'success':($ls=='rejected'?'danger':'warning') }}">{{ ucfirst($ls) }}</span>
                </td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i></button>
                        <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="fas fa-times"></i></button>
                        <button class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="newLeaveModal" title="New Leave Request">
    <form>
        <x-form-select name="type" label="Type" :options="['student'=>'Student','staff'=>'Staff']" />
        <x-form-select name="user" label="Select Person" :options="['1'=>'John Doe','2'=>'Sarah Wilson']" />
        <x-form-select name="leave_type" label="Leave Type" :options="['sick'=>'Sick Leave','casual'=>'Casual Leave','annual'=>'Annual Leave','emergency'=>'Emergency','other'=>'Other']" />
        <x-form-input name="from_date" label="From Date" type="date" />
        <x-form-input name="to_date" label="To Date" type="date" />
        <x-form-textarea name="reason" label="Reason" rows="3" required />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Submit Request</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection