@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Management</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Students</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('student.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Student</a>
        <button class="btn btn-outline-secondary"><i class="fas fa-upload me-1"></i>Import</button>
        <button class="btn btn-outline-success"><i class="fas fa-file-excel me-1"></i>Export</button>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Search</label><input type="text" class="form-control form-control-sm" placeholder="Name, ID, phone..."></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>All</option><option>KG</option><option>Grade 1</option><option>Grade 2</option><option>Grade 3</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Section</label><select class="form-select form-select-sm"><option>All</option><option>A</option><option>B</option><option>C</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">House</label><select class="form-select form-select-sm"><option>All</option><option>Red House</option><option>Blue House</option><option>Green House</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>Active</option><option>Graduated</option><option>Transferred</option><option>Suspended</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
        <div class="col-md-1"><button class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo-alt"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['ID', 'Photo', 'Name', 'Class-Section', 'Admission No.', 'Phone', 'House', 'Status', 'Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>{{ sprintf('STU-%04d', $i) }}</td>
                <td><div class="avatar-circle bg-{{ ['primary','success','info','warning','danger'][array_rand(['primary','success','info','warning','danger'])] }}" style="width:36px;height:36px;font-size:0.8rem">{{ ['JD','AS','EC','DB','FG','GL','HW','IC','JK','AL'][$i-1] }}</div></td>
                <td><a href="{{ route('student.show', $i) }}" class="text-decoration-none fw-semibold">{{ ['John Doe','Alice Smith','Emily Clark','David Brown','Frank Green','Grace Lee','Henry Wilson','Ivy Chen','Jack Davis','Kathy Adams'][$i-1] }}</a></td>
                <td>Grade {{ rand(1,12) }}-{{ ['A','B','C'][array_rand(['A','B','C'])] }}</td>
                <td>ADM-{{ sprintf('%04d', $i+100) }}</td>
                <td>+1-555-{{ sprintf('%04d', $i+1000) }}</td>
                <td>{{ ['Red','Blue','Green','Yellow'][array_rand(['Red','Blue','Green','Yellow'])] }} House</td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('student.show', $i) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('student.edit', $i) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-pagination :paginator="$students" />
@endsection