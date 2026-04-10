@extends('layouts.auth-layout.auth')

@section('title', 'OTP Verification')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="text-center mb-2">OTP Verification</h4>
                    <p class="text-center text-muted mb-4">
                        Enter the 6-digit code sent to your email.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">OTP Code</label>
                            <input type="text" name="otp" class="form-control text-center" maxlength="6"
                                placeholder="000000" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Verify
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small">
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
