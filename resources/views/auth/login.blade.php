@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="card auth-card">
        <div class="card-header">
            <div class="auth-logo"><i class="fas fa-graduation-cap"></i></div>
            <h4 class="auth-title">Welcome Back</h4>
            <p class="auth-subtitle">Sign in to your account</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-auth">Sign In</button>
            </form>
        </div>
        <div class="card-footer">
            <p class="mb-1"><a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Your Password?</a></p>
            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
        </div>
    </div>
@endsection
