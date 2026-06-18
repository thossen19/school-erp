@extends('layouts.app')
@section('title', 'Fee Structure')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-money-bill-wave me-2"></i>Fee Structure</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Fees</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('fees.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Fee Structure</a>
        <a href="{{ route('fees.collection') }}" class="btn btn-outline-success"><i class="fas fa-hand-holding-usd me-1"></i>Collections</a>
        <a href="{{ route('fees.due-tracking') }}" class="btn btn-outline-warning"><i class="fas fa-exclamation-triangle me-1"></i>Due Tracking</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>All Classes</option><option>Grade 1</option><option>Grade 2</option></select></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Academic Year</label><select class="form-select form-select-sm"><option>2025-2026</option></select></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Fee Type</label><select class="form-select form-select-sm"><option>All</option><option>Tuition</option><option>Transport</option><option>Hostel</option><option>Lab</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Fee Head','Class','Amount','Frequency','Due Date','Late Fee','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><span class="fw-semibold">{{ ['Tuition Fee','Transport Fee','Lab Fee','Sports Fee','Library Fee','Hostel Fee','Activity Fee','Exam Fee'][$i-1] }}</span></td>
                <td>Grade {{ rand(1,12) }}</td>
                <td>${{ number_format(rand(500,5000),2) }}</td>
                <td>{{ ['Monthly','Quarterly','Yearly','One-Time'][$i%4] }}</td>
                <td>15th of Month</td>
                <td>${{ rand(10,100) }}</td>
                <td><span class="badge bg-{{ $i%2==0?'success':'warning' }}">{{ $i%2==0?'Active':'Inactive' }}</span></td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('fees.show', $i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('fees.edit', $i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection