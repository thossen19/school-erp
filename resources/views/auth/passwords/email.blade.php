@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="card auth-card">
        <div class="card-header">
            <div class="auth-logo"><i class="fas fa-key"></i></div>
            <h4 class="auth-title">Reset Password</h4>
            <p class="auth-subtitle">Enter your email to receive a reset link</p>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-auth">Send Password Reset Link</button>
            </form>
        </div>
        <div class="card-footer">
            <p class="mb-0"><a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></p>
        </div>
    </div>
@endsection
