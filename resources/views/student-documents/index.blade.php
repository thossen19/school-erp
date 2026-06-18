@extends('layouts.app')
@section('title', 'Student Documents')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Student Documents</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Documents</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add New</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#', 'Student', 'Document Type', 'Number', 'Verified', 'Expiry', 'Actions']">
            @forelse($documents as $document)
            <tr>
                <td>{{ $document->id }}</td>
                <td>{{ $document->student?->first_name }} {{ $document->student?->last_name }}</td>
                <td>{{ $document->document_type }}</td>
                <td>{{ $document->document_number ?? '-' }}</td>
                <td><span class="badge {{ $document->verified ? 'bg-success' : 'bg-warning' }}">{{ $document->verified ? 'Verified' : 'Pending' }}</span></td>
                <td>{{ $document->expiry_date ? $document->expiry_date->format('d-m-Y') : 'N/A' }}</td>
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
<x-pagination :paginator="$documents" />
@endsection
