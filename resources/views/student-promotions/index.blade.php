@extends('layouts.app')
@section('title', 'Student Promotions')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-arrow-up me-2"></i>Student Promotions</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Promotions</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add New</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Student', 'From Class', 'To Class', 'Date', 'Status', 'Actions']">
            @forelse($promotions as $promotion)
            <tr>
                <td>{{ $promotion->id }}</td>
                <td>{{ $promotion->student?->first_name }} {{ $promotion->student?->last_name }}</td>
                <td>{{ $promotion->from_class_id }}</td>
                <td>{{ $promotion->to_class_id }}</td>
                <td>{{ $promotion->promotion_date->format('d-m-Y') }}</td>
                <td><span class="badge {{ $promotion->status === 'promoted' ? 'bg-success' : ($promotion->status === 'graduated' ? 'bg-primary' : 'bg-secondary') }}">{{ ucfirst($promotion->status) }}</span></td>
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
<x-pagination :paginator="$promotions" />
@endsection
