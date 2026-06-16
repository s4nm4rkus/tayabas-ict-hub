@extends('layouts.auth')

@section('title', 'Change Password')

@section('card')
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="otp-icon" style="background:rgba(201,168,76,0.12);color:#C9A84C;">
                <i class="bi bi-key-fill"></i>
            </div>
            <div class="auth-eyebrow">Account Setup</div>
            <h1 class="auth-card-title">Change<br>Password</h1>
            <p class="auth-card-sub">You must set a new password before continuing.</p>
        </div>

        @if ($errors->any())
            <div class="auth-alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="newPassword" class="form-input" placeholder="••••••••"
                        required autofocus>
                    <button type="button" class="toggle-password" onclick="toggleNew()">
                        <i class="bi bi-eye" id="toggleNew"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password_confirmation" id="confirmPassword" class="form-input"
                        placeholder="••••••••" required>
                    <button type="button" class="toggle-password" onclick="toggleConfirm()">
                        <i class="bi bi-eye" id="toggleConfirm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Update Password</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="auth-card-footer">
            <i class="bi bi-shield-lock"></i>
            You will be redirected after updating
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleNew() {
            const input = document.getElementById('newPassword');
            const icon = document.getElementById('toggleNew');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function toggleConfirm() {
            const input = document.getElementById('confirmPassword');
            const icon = document.getElementById('toggleConfirm');
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
