@extends('layouts.app')
@section('title', 'Student Registration')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Registration</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Registration</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#regModal"><i class="fas fa-plus me-1"></i>Register Student</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Registered Students</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Admission No','Name','Status','Registered']">
            @forelse($students as $s)
            <tr>
                    <td>{{ $loop->iteration }}</td>
                <td><small class="text-muted">{{ $s->admission_no }}</small></td>
                <td class="fw-semibold">{{ $s->first_name }} {{ $s->last_name ?? '' }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td>{{ \Carbon\Carbon::parse($s->created_at)->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No students registered yet</td></tr>
            @endforelse
        </x-table>
    </div>
</div>

<x-modal id="regModal" title="Register Student from Approved Form">
    <form method="POST" action="{{ route('admissions.student-registration.store') }}">
        @csrf
        <x-form-select name="admission_form_id" label="Approved Application" :options="$approvedForms->pluck('applicant_name','id')->toArray()" required />
        <div class="row g-2">
            <div class="col-md-6"><x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')->toArray()" required /></div>
            <div class="col-md-6"><x-form-input name="roll_number" label="Roll Number" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#regModal form').submit()">Register</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
