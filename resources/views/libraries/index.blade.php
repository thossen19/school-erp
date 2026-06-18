@extends('layouts.app')
@section('title', 'Library')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Library Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Library</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('library.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Book</a>
        <a href="{{ route('library.issues') }}" class="btn btn-outline-info"><i class="fas fa-book-open me-1"></i>Issues</a>
        <a href="{{ route('library.members') }}" class="btn btn-outline-success"><i class="fas fa-id-card me-1"></i>Members</a>
        <a href="{{ route('library.fines') }}" class="btn btn-outline-warning"><i class="fas fa-coins me-1"></i>Fines</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Search</label><input type="text" class="form-control form-control-sm" placeholder="Book title, author, ISBN..."></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Category</label><select class="form-select form-select-sm"><option>All</option><option>Fiction</option><option>Non-Fiction</option><option>Academic</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>Available</option><option>Issued</option><option>Lost</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','ISBN','Title','Author','Category','Copies','Available','Rack','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><small>{{ rand(1000000000000,9999999999999) }}</small></td>
                <td><a href="{{ route('library.show',$i) }}" class="text-decoration-none fw-semibold">{{ ['To Kill a Mockingbird','1984','The Great Gatsby','Mathematics Grade 10','Physics Textbook','World History','Biology Basics','English Grammar'][$i-1] }}</a></td>
                <td>{{ ['Harper Lee','George Orwell','F. Scott Fitzgerald','R. Stewart','H. Brown','J. Adams','M. Clark','P. White'][$i-1] }}</td>
                <td>{{ ['Fiction','Fiction','Fiction','Academic','Academic','Academic','Academic','Academic'][$i-1] }}</td>
                <td>{{ rand(2,10) }}</td>
                <td>{{ rand(0,5) }}</td>
                <td>{{ ['A-1','A-2','B-1','B-2','C-1','C-2','D-1','D-2'][$i-1] }}</td>
                <td><span class="badge bg-{{ ['success','warning','danger'][$i%3] }}">{{ ['Available','Issued','Lost'][$i%3] }}</span></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('library.show',$i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('library.edit',$i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-pagination :paginator="$books" />
@endsection