@extends('layouts.app')
@section('title', 'Student Transfers')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Student Transfers</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Transfers</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add New</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Student', 'Transfer Date', 'From Class', 'To Class', 'Certificate', 'Actions']">
            @forelse($transfers as $transfer)
            <tr>
                <td>{{ $transfer->id }}</td>
                <td>{{ $transfer->student?->first_name }} {{ $transfer->student?->last_name }}</td>
                <td>{{ $transfer->transfer_date->format('d-m-Y') }}</td>
                <td>{{ $transfer->from_class_id }}</td>
                <td>{{ $transfer->to_class_id }}</td>
                <td>{{ $transfer->transfer_certificate_no ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-edit"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$transfers" />
@endsection
