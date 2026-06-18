@extends('layouts.app')
@section('title', 'Team Section')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-users-cog me-2"></i>Team Section</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route_if_exists('settings.general') }}">Settings</a></li><li class="breadcrumb-item active">Team</li></ol></nav>
    </div>
</div>
<form method="POST" action="{{ route('homepage.team.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-user-tie me-2"></i>Team Members</h6></div>
        <div class="card-body">
            @for($i = 1; $i <= 8; $i++)
            <div class="mb-3 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-1"><span class="badge bg-secondary">#{{ $i }}</span></div>
                    <div class="col-md-3"><input type="text" name="member_name_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_name_'.$i] ?? '' }}" placeholder="Full name"></div>
                    <div class="col-md-3"><input type="text" name="member_designation_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_designation_'.$i] ?? '' }}" placeholder="Designation / role"></div>
                    <div class="col-md-3"><input type="file" name="member_photo_{{ $i }}" class="form-control form-control-sm" accept="image/*"></div>
                    <div class="col-md-2">
                        <input type="text" name="member_order_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_order_'.$i] ?? $i }}" placeholder="Order">
                    </div>
                </div>
                <div class="row g-2 mt-1">
                    <div class="col"><input type="text" name="member_social_fb_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_social_fb_'.$i] ?? '' }}" placeholder="Facebook URL"></div>
                    <div class="col"><input type="text" name="member_social_tw_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_social_tw_'.$i] ?? '' }}" placeholder="X (Twitter) URL"></div>
                    <div class="col"><input type="text" name="member_social_li_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_social_li_'.$i] ?? '' }}" placeholder="LinkedIn URL"></div>
                    <div class="col"><input type="text" name="member_social_in_{{ $i }}" class="form-control form-control-sm" value="{{ $settings['member_social_in_'.$i] ?? '' }}" placeholder="Instagram URL"></div>
                </div>
                @if($settings['member_photo_'.$i] ?? false)
                <div class="mt-1"><img src="{{ asset('storage/'.$settings['member_photo_'.$i]) }}" class="rounded-circle" style="max-height:40px"></div>
                @endif
            </div>
            @endfor
            <small class="text-muted">Up to 8 team members</small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4 mt-3"><i class="fas fa-save me-1"></i>Save Changes</button>
</form>
@endsection