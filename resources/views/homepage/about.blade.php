@extends('layouts.app')
@section('title', 'About Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-info-circle me-2"></i>About Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">About</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.about.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-heading me-2"></i>Section Content</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Section Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $settings['title'] ?? '' }}" placeholder="About Our School">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Content</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Write about your school...">{{ $settings['content'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Image</label>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                        @if($settings['image'] ?? false)<div class="mt-1"><img src="{{ asset('storage/'.$settings['image']) }}" class="img-fluid rounded" style="max-height:80px"></div>@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-simple me-2"></i>Statistics / Counters</h6></div>
                <div class="card-body">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="mb-2 p-2 border rounded">
                        <div class="row g-1">
                            <div class="col-6"><input type="text" name="stat_label_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['stat_label_'.$i] ?? '' }}" placeholder="Label"></div>
                            <div class="col-6"><input type="text" name="stat_value_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['stat_value_'.$i] ?? '' }}" placeholder="Value"></div>
                        </div>
                    </div>
                    @endfor
                    <small class="text-muted">Add up to 4 statistics</small>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection