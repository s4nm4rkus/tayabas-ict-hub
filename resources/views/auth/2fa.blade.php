@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('card')
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="otp-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="auth-eyebrow">Security Verification</div>
            <h1 class="auth-card-title">Two-Factor<br>Auth</h1>
            <p class="auth-card-sub">Enter the 6-digit code sent to your email</p>
        </div>

        @if ($errors->any())
            <div class="auth-alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="auth-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Verification Code</label>
                <div class="input-wrapper">
                    <i class="bi bi-key input-icon"></i>
                    <input type="text" name="otp" class="form-input otp-input" maxlength="6" placeholder="000000"
                        required autofocus inputmode="numeric" pattern="[0-9]*"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                </div>
                <div class="otp-hint">Check your inbox including spam/junk folder</div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Verify Code</span>
                <i class="bi bi-shield-check"></i>
            </button>
        </form>

        <div class="resend-section">
            <span class="resend-text">Didn't receive the code?</span>
            {{-- <form method="POST" action="{{ route('2fa.resend') }}" style="display:inline;">
                @csrf
                </form> --}}
            <button type="submit" class="btn-resend" id="resendBtn" onclick="startCountdown()">
                Resend OTP
            </button>

            <span class="countdown" id="countdown" style="display:none;">
                Resend in <span id="timer">60</span>s
            </span>
        </div>

        <div class="auth-back">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>

        <div class="auth-card-footer">
            <i class="bi bi-clock"></i>
            OTP expires in 10 minutes
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function startCountdown() {
            const btn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            const timer = document.getElementById('timer');
            let seconds = 60;

            btn.style.display = 'none';
            countdown.style.display = 'inline';

            const interval = setInterval(() => {
                seconds--;
                timer.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                    btn.style.display = 'inline';
                    countdown.style.display = 'none';
                    timer.textContent = '60';
                }
            }, 1000);
        }
    </script>
@endpush
