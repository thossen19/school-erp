@extends('layouts.app')

@section('title', 'Library Reports')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Library Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-book fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalBooks }}</h3>
                <small class="text-muted">Total Books</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-2x text-success mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $availableBooks }}</h3>
                <small class="text-muted">Available Copies</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-id-card fa-2x text-warning mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalMembers }}</h3>
                <small class="text-muted">Library Members</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-exchange-alt fa-2x text-info mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalIssues }}</h3>
                <small class="text-muted">Total Issues</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Issue Status</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Currently Issued</span><span class="fw-bold">{{ $issuedCount }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Overdue</span><span class="fw-bold text-danger">{{ $overdueCount }}</span></div>
                <div class="d-flex justify-content-between"><span>Lost</span><span class="fw-bold text-dark">{{ $lostCount }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Fine Summary</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Total Fines</span><span class="fw-bold">৳{{ number_format($totalFines, 2) }}</span></div>
                <div class="d-flex justify-content-between"><span>Collected</span><span class="fw-bold text-success">৳{{ number_format($collectedFines, 2) }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Quick Actions</h5></div>
            <div class="card-body">
                <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm w-100 mb-2"><i class="fas fa-plus me-1"></i>Add New Book</a>
                <a href="{{ route('book-issues.create') }}" class="btn btn-success btn-sm w-100 mb-2"><i class="fas fa-book-open me-1"></i>Issue Book</a>
                <a href="{{ route('library.barcode') }}" class="btn btn-secondary btn-sm w-100"><i class="fas fa-barcode me-1"></i>Print Barcodes</a>
            </div>
        </div>
    </div>
</div>
@endsection
