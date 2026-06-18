@extends('layouts.app')
@section('title', 'FAQ Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-question-circle me-2"></i>FAQ Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">FAQ</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.faq.update') }}">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-folder me-2"></i>FAQ Categories</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 4; $i++)
            <div class="mb-1">
                <input type="text" name="faq_category_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['faq_category_'.$i] ?? '' }}" placeholder="Category {{ $i }}">
            </div>
            @endfor
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-comments me-2"></i>Questions & Answers</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 10; $i++)
            <div class="mb-2 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-1"><span class="badge bg-secondary">#{{ $i }}</span></div>
                    <div class="col-md-5"><input type="text" name="faq_question_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['faq_question_'.$i] ?? '' }}" placeholder="Question"></div>
                    <div class="col-md-6">
                        <select name="faq_category_id_{{ $i }}" class="form-select form-select-sm">
                            <option value="">No category</option>
                            @for($j = 1; $j <= 4; $j++)
                            <option value="{{ $j }}" {{ ($settings['faq_category_id_'.$i] ?? '') == (string)$j ? 'selected' : '' }}>Category {{ $j }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-12">
                        <textarea name="faq_answer_{{ $i }}" class="form-control form-control-sm" rows="2" placeholder="Answer">{{ $settings['faq_answer_'.$i] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endfor
            <small class="text-muted">Up to 10 FAQ items</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection