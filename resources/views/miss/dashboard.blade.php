@extends('layouts.app')
@section('title', 'MIS Dashboard')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>MIS Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('mis.index') }}">MIS</a></li><li class="breadcrumb-item active">Dashboard</li></ol></nav>
    </div>
</div>
@include('dashboard.index')
@endsection