@extends('layouts.app')
@section('title', 'Alumni Directory')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-graduation-cap me-2"></i>Alumni Directory</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Alumni</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('alumni.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Alumni</a>
        <a href="{{ route('alumni.events') }}" class="btn btn-outline-info"><i class="fas fa-calendar me-1"></i>Events</a>
        <a href="{{ route('alumni.donations') }}" class="btn btn-outline-success"><i class="fas fa-donate me-1"></i>Donations</a>
        <a href="{{ route('alumni.jobs') }}" class="btn btn-outline-warning"><i class="fas fa-briefcase me-1"></i>Jobs</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Search</label><input type="text" class="form-control form-control-sm" placeholder="Name, batch year..."></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Batch Year</label><select class="form-select form-select-sm"><option>All</option><option>2025</option><option>2024</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Graduation Year','Class','Current Occupation','Company','Phone','Email','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="{{ route('alumni.show',$i) }}" class="text-decoration-none fw-semibold">{{ ['Alice Johnson','Bob Smith','Carol Davis','David Lee','Emma Wilson','Frank Brown','Grace Taylor','Henry Clark'][$i-1] }}</a></td>
                <td>20{{ sprintf('%02d',rand(15,24)) }}</td>
                <td>Grade {{ rand(10,12) }}</td>
                <td>{{ ['Software Engineer','Doctor','Teacher','Business Owner','Architect','Lawyer','Designer','Accountant'][$i-1] }}</td>
                <td>{{ ['Tech Corp','City Hospital','School','Own Business','Design Firm','Law Office','Creative Agency','Finance Inc'][$i-1] }}</td>
                <td>+1-555-{{ sprintf('%04d',$i+11000) }}</td>
                <td><small>{{ strtolower(str_replace(' ','','alumni'.$i)).'@email.com' }}</small></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('alumni.show',$i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('alumni.edit',$i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection