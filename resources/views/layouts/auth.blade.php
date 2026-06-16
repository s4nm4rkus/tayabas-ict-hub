<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — SDO Tayabas City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --navy: #0B1F3A;
            --blue: #1A4A8A;
            --gold: #C9A84C;
            --white: #FFFFFF;
            --light: #F4F7FB;
            --muted: #6B7A90;
            --border: rgba(26, 74, 138, 0.12);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: var(--navy);
            overflow: hidden;
            height: 100vh;
        }

        /* ── Hero shell (same as landing hero) ── */
        .auth-hero-shell {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
        }

        /* Same video positioning as landing page hero */
        .hero-video {
            position: absolute;
            top: 0;
            right: -10%;
            height: 100%;
            width: auto;
            min-width: 60%;
            object-fit: cover;
            object-position: center center;
            z-index: 0;
        }

        /* Same gradient overlay as landing hero */
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to right,
                    #ffffff 0%,
                    #ffffff 32%,
                    rgba(255, 255, 255, 0.97) 42%,
                    rgba(255, 255, 255, 0.80) 52%,
                    rgba(255, 255, 255, 0) 64%,
                    rgba(255, 255, 255, 0) 78%);
            pointer-events: none;
        }

        /* Card lives here — same z-index slot as hero-content */
        .auth-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            margin-left: 12%;
            padding: 48px 0;
        }

        /* ── Brand row ── */
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 32px;
            opacity: 0;
            animation: fadeUp 0.6s ease 0.05s forwards;
        }

        .auth-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .auth-brand-text strong {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
        }

        .auth-brand-text span {
            font-size: 10.5px;
            color: var(--muted);
        }

        /* ── The card ── */
        .auth-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 36px 40px;
            box-shadow: 0 8px 40px rgba(11, 31, 58, 0.10);
            opacity: 0;
            animation: fadeUp 0.7s ease 0.15s forwards;
        }

        .auth-card-header {
            margin-bottom: 24px;
        }

        /* Eyebrow — mirrors hero-eyebrow */
        .auth-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .auth-eyebrow::before {
            content: '';
            width: 24px;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
            display: block;
        }

        .auth-card-title {
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 800;
            color: var(--navy);
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .auth-card-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* OTP icon */
        .otp-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(26, 74, 138, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--blue);
            margin-bottom: 14px;
        }

        /* ── Alert ── */
        .auth-alert {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            color: #B91C1C;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }

        .auth-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            color: #047857;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }

        /* ── Form ── */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 11px 13px 11px 38px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13.5px;
            font-family: 'Poppins', sans-serif;
            color: var(--navy);
            background: var(--light);
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--blue);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 74, 138, 0.08);
        }

        .form-input::placeholder {
            color: rgba(107, 122, 144, 0.45);
        }

        .otp-input {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.3em;
            padding-left: 38px;
        }

        .otp-hint {
            font-size: 11.5px;
            color: var(--muted);
            margin-top: 5px;
        }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: var(--navy);
        }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s;
            box-shadow: 0 6px 20px rgba(26, 74, 138, 0.3);
            margin-top: 6px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(26, 74, 138, 0.42);
        }

        /* ── Resend ── */
        .resend-section {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
        }

        .resend-text {
            color: var(--muted);
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--blue);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            padding: 0;
            margin-left: 4px;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .btn-resend:hover {
            color: var(--navy);
        }

        .countdown {
            color: var(--muted);
            font-size: 13px;
            margin-left: 4px;
        }

        /* ── Back ── */
        .auth-back {
            text-align: center;
            margin-top: 14px;
        }

        .back-link {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--navy);
        }

        /* ── Card Footer ── */
        .auth-card-footer {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            font-size: 11.5px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
        }

        /* ── Stats row below card (mirrors hero-stats) ── */
        .auth-stats {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.30s forwards;
        }

        .auth-stat {
            padding: 10px 22px 10px 0;
            position: relative;
        }

        .auth-stat:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 11px;
            top: 25%;
            bottom: 25%;
            width: 1px;
            background: rgba(26, 74, 138, 0.15);
        }

        .auth-stat-num {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .auth-stat-label {
            font-size: 9.5px;
            color: var(--muted);
            font-weight: 500;
            margin-top: 1px;
        }

        /* ── Animations (same keyframe as landing) ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            body {
                overflow: auto;
                height: auto;
            }

            .auth-hero-shell {
                min-height: 100svh;
                height: auto;
                align-items: flex-end;
            }

            .hero-video {
                right: auto;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                min-width: unset;
                object-fit: cover;
                object-position: center 10%;
            }

            .hero-overlay {
                background: linear-gradient(to top,
                        #ffffff 0%,
                        #ffffff 40%,
                        rgba(255, 255, 255, 0.97) 55%,
                        rgba(255, 255, 255, 0.75) 68%,
                        rgba(255, 255, 255, 0.10) 85%,
                        rgba(255, 255, 255, 0.00) 100%);
            }

            .auth-content {
                margin-left: 0;
                max-width: 100%;
                padding: 40px 5% 52px;
            }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 22px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .auth-brand,
            .auth-card,
            .auth-stats {
                animation: none;
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="auth-hero-shell">

        {{-- Same video as landing hero --}}
        <video class="hero-video" src="{{ asset('storage/logo-3d.mp4') }}" autoplay loop muted playsinline
            poster="#">
        </video>

        {{-- Same gradient overlay --}}
        <div class="hero-overlay"></div>

        {{-- Card lives in the hero-content slot --}}
        <div class="auth-content">

            {{-- Brand --}}
            <div class="auth-brand">
                <div class="auth-brand-icon">
                    <img src="{{ asset('storage/logo-nav.png') }}" alt="SDO Logo" onerror="this.style.display='none'">
                </div>
                <div class="auth-brand-text">
                    <strong>SDO Tayabas City</strong>
                    <span>Schools Division Office</span>
                </div>
            </div>

            {{-- Auth card (login / 2fa inject here) --}}
            @yield('card')

            {{-- Stat strip mirrors hero-stats --}}
            <div class="auth-stats">
                <div class="auth-stat">
                    <div class="auth-stat-num">DepEd</div>
                    <div class="auth-stat-label">Department of Education</div>
                </div>
                <div class="auth-stat">
                    <div class="auth-stat-num">IV-A</div>
                    <div class="auth-stat-label">CALABARZON Region</div>
                </div>
                <div class="auth-stat">
                    <div class="auth-stat-num">SDO</div>
                    <div class="auth-stat-label">Division Level Office</div>
                </div>
            </div>

        </div>
    </div>

    @stack('scripts')
</body>

</html>
