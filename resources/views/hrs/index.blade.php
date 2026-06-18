@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-users me-2"></i>Employee Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Employees</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Employee</a>
        <a href="{{ route('hr.departments') }}" class="btn btn-outline-info"><i class="fas fa-building me-1"></i>Departments</a>
        <a href="{{ route('hr.recruitment') }}" class="btn btn-outline-success"><i class="fas fa-user-plus me-1"></i>Recruitment</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Search</label><input type="text" class="form-control form-control-sm" placeholder="Name, ID, phone..."></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Department</label><select class="form-select form-select-sm"><option>All</option><option>Teaching</option><option>Admin</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Designation</label><select class="form-select form-select-sm"><option>All</option><option>Teacher</option><option>Principal</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>Active</option><option>Inactive</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['ID','Name','Department','Designation','Phone','Email','Join Date','Status','Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>EMP-{{ sprintf('%04d',$i) }}</td>
                <td><a href="{{ route('hr.show',$i) }}" class="text-decoration-none fw-semibold">{{ ['John Smith','Sarah Johnson','Robert Brown','Emily Davis','Michael Wilson','Jessica Lee','David Clark','Laura White','James Hall','Emma Moore'][$i-1] }}</a></td>
                <td>{{ ['Teaching','Administration','Accounts','Library','Sports','IT','Science','Math','English','Arts'][$i-1] }}</td>
                <td>{{ ['Senior Teacher','Accountant','Librarian','Coach','IT Admin','Science Teacher','Math Teacher','English Teacher','Arts Teacher','Principal'][$i-1] }}</td>
                <td>+1-555-{{ sprintf('%04d',$i+2000) }}</td>
                <td><small>{{ strtolower(str_replace(' ','.',$i==1?'John Smith':'employee'.$i)).'@school.edu' }}</small></td>
                <td>202{{ rand(0,5) }}-{{ sprintf('%02d',rand(1,12)) }}-{{ sprintf('%02d',rand(1,28)) }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('hr.show',$i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('hr.edit',$i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-pagination :paginator="$employees" />
@endsection