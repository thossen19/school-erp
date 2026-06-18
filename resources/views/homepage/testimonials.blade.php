@extends('layouts.app')
@section('title', 'Testimonials')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-quote-right me-2"></i>Testimonials</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Testimonials</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.testimonials.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Customer Reviews</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 6; $i++)
            <div class="mb-3 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" name="reviewer_name_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['reviewer_name_'.$i] ?? '' }}" placeholder="Reviewer name"></div>
                    <div class="col-md-4">
                        <select name="review_rating_{{ $i }}" class="form-select form-select-sm">
                            <option value="5" {{ ($settings['review_rating_'.$i] ?? '5') == '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ ($settings['review_rating_'.$i] ?? '') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ ($settings['review_rating_'.$i] ?? '') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ ($settings['review_rating_'.$i] ?? '') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ ($settings['review_rating_'.$i] ?? '') == '1' ? 'selected' : '' }}>1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-4"><input type="file" name="reviewer_photo_{{ $i }}" class="form-control form-control-sm" accept="image/*"></div>
                    <div class="col-12 mt-1">
                        <textarea name="review_text_{{ $i }}" class="form-control form-control-sm" rows="2" placeholder="Review text">{{ $settings['review_text_'.$i] ?? '' }}</textarea>
                    </div>
                    @if($settings['reviewer_photo_'.$i] ?? false)
                    <div class="col-12"><img src="{{ asset('storage/'.$settings['reviewer_photo_'.$i]) }}" class="rounded-circle" style="max-height:40px"></div>
                    @endif
                </div>
            </div>
            @endfor
            <small class="text-muted">Up to 6 testimonials</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection