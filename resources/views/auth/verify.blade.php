@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
    <div class="card auth-card">
        <div class="card-header">
            <div class="auth-logo"><i class="fas fa-envelope"></i></div>
            <h4 class="auth-title">Verify Your Email</h4>
            <p class="auth-subtitle">A verification link has been sent to your email</p>
        </div>
        <div class="card-body">
            @if (session('resent'))
                <div class="alert alert-success">A fresh verification link has been sent to your email address.</div>
            @endif
            <p>Before proceeding, please check your email for a verification link.</p>
            <p>If you did not receive the email, <a href="{{ route('verification.resend') }}" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">click here to request another</a>.</p>
            <form id="resend-form" method="POST" action="{{ route('verification.resend') }}" class="d-none">
                @csrf
            </form>
        </div>
    </div>
@endsection
