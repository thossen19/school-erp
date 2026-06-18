@extends('layouts.app')
@section('title', 'Statistics / Counters')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Statistics / Counters</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Statistics</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.statistics.update') }}">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calculator me-2"></i>Counters</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body text-center py-3">
                            <label class="form-label fw-semibold">Projects Completed</label>
                            <input type="text" name="projects_completed" class="form-control text-center" value="{{ $settings['projects_completed'] ?? '5000' }}" placeholder="e.g. 5000+">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body text-center py-3">
                            <label class="form-label fw-semibold">Clients Served</label>
                            <input type="text" name="clients_served" class="form-control text-center" value="{{ $settings['clients_served'] ?? '500' }}" placeholder="e.g. 500+">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body text-center py-3">
                            <label class="form-label fw-semibold">Awards</label>
                            <input type="text" name="awards" class="form-control text-center" value="{{ $settings['awards'] ?? '50' }}" placeholder="e.g. 50+">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body text-center py-3">
                            <label class="form-label fw-semibold">Custom Counter 1</label>
                            <input type="text" name="custom_counter_label" class="form-control form-control-sm mb-1" value="{{ $settings['custom_counter_label'] ?? '' }}" placeholder="Label">
                            <input type="text" name="custom_counter_value" class="form-control form-control-sm" value="{{ $settings['custom_counter_value'] ?? '' }}" placeholder="Value">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection