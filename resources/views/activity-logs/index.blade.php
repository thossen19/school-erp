@extends('layouts.app')
@section('title', 'Activity Logs')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-history me-2"></i>Activity Logs</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Activity Logs</li></ol></nav>
    </div>
    <button class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i>Clear Logs</button>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">User</label><select class="form-select form-select-sm"><option>All Users</option><option>Admin</option><option>Teacher 1</option></select></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Module</label><select class="form-select form-select-sm"><option>All Modules</option><option>Student</option><option>Fees</option><option>Attendance</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Action</label><select class="form-select form-select-sm"><option>All</option><option>Create</option><option>Update</option><option>Delete</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">From Date</label><input type="date" class="form-control form-control-sm"></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
        <div class="col-md-1"><button class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo-alt"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1,10) as $i)
                    @php
                        $actions = ['Create','Update','Delete','View','Login','Export','Print','Update','Create','Delete'];
                        $modules = ['Student','Fees','Attendance','Student','System','Fees','Certificate','Timetable','User','Student'];
                        $descriptions = [
                            'Added new student John Doe',
                            'Updated fee payment for Jane Smith',
                            'Marked attendance for Grade 10A',
                            'Viewed student profile STU-0001',
                            'User logged in',
                            'Exported fee collection report',
                            'Printed certificate for Alice',
                            'Updated timetable - Grade 9B',
                            'Created new user account',
                            'Deleted old student record'
                        ];
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="fw-semibold">{{ ['Admin','Teacher 1','Teacher 2','Admin','Admin','Teacher 1','Admin','Teacher 2','Admin','Teacher 1'][$i-1] }}</td>
                        <td><span class="badge bg-{{ $i%2==0?'info':'primary' }}">{{ $i%2==0?'Teacher':'Admin' }}</span></td>
                        <td>{{ $modules[$i-1] }}</td>
                        <td>
                            <span class="badge bg-{{ $actions[$i-1]=='Create'?'success':($actions[$i-1]=='Delete'?'danger':($actions[$i-1]=='Update'?'warning':'secondary')) }}">
                                {{ $actions[$i-1] }}
                            </span>
                        </td>
                        <td><small>{{ $descriptions[$i-1] }}</small></td>
                        <td><small>192.168.1.{{ rand(10,255) }}</small></td>
                        <td><small>Jun {{ $i }}, 2026 {{ sprintf('%02d',rand(8,17)) }}:{{ sprintf('%02d',rand(0,59)) }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$activityLogs" />
@endsection