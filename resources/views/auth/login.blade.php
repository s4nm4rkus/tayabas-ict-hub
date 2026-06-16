@extends('layouts.auth')

@section('title', 'Login')

@section('card')
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="auth-eyebrow">Employee Portal</div>
            <h1 class="auth-card-title">Welcome<br>Back</h1>
            <p class="auth-card-sub">Enter your credentials to continue</p>
        </div>

        @if ($errors->any())
            <div class="auth-alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="username" class="form-input" value="{{ old('username') }}"
                        placeholder="you@deped.gov.ph" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="passwordInput" class="form-input" placeholder="••••••••"
                        required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Log In</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="auth-card-footer">
            <i class="bi bi-shield-lock"></i>
            Secured with OTP two-factor authentication
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
@endpush
