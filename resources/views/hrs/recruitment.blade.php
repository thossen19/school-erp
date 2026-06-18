@extends('layouts.app')
@section('title', 'Recruitment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Recruitment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item active">Recruitment</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newJobModal"><i class="fas fa-plus me-1"></i>Post Job</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><x-stats-card title="Open Positions" value="5" icon="fa-briefcase" color="primary" /></div>
    <div class="col-md-3"><x-stats-card title="Applications" value="24" icon="fa-file-alt" color="info" /></div>
    <div class="col-md-3"><x-stats-card title="Shortlisted" value="12" icon="fa-user-check" color="success" /></div>
    <div class="col-md-3"><x-stats-card title="Interviews" value="8" icon="fa-handshake" color="warning" /></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Job Title','Department','Type','Applications','Posted Date','Deadline','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Senior Math Teacher','English Teacher','Lab Assistant','Accountant','Sports Coach'][$i-1] }}</td>
                <td>{{ ['Teaching','Teaching','Science','Accounts','Sports'][$i-1] }}</td>
                <td>{{ ['Full-time','Full-time','Part-time','Full-time','Contract'][$i-1] }}</td>
                <td><span class="badge bg-info">{{ rand(3,12) }}</span></td>
                <td>Jun {{ $i }}, 2026</td>
                <td>Jun {{ $i+30 }}, 2026</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="newJobModal" title="Post New Job">
    <form>
        <x-form-input name="title" label="Job Title" required />
        <x-form-select name="department" label="Department" :options="['teaching'=>'Teaching','admin'=>'Administration']" />
        <x-form-select name="type" label="Employment Type" :options="['full_time'=>'Full Time','part_time'=>'Part Time','contract'=>'Contract']" />
        <x-form-textarea name="description" label="Job Description" rows="4" required />
        <x-form-textarea name="requirements" label="Requirements" rows="3" />
        <x-form-input name="deadline" label="Application Deadline" type="date" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Post Job</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection