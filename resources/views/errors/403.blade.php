@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="bi bi-shield-exclamation text-danger" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 fw-bold text-danger">403</h1>
            <h4 class="text-muted mb-4">Forbidden</h4>
            <p class="lead mb-4">You do not have permission to access this page.</p>
            @if($exception?->getMessage() && $exception->getMessage() !== 'Forbidden')
                <div class="alert alert-warning d-inline-block">
                    <strong>Required permission:</strong> {{ $exception->getMessage() }}
                </div>
            @endif
            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary ms-2">Go Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
