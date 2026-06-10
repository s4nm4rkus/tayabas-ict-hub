{{-- resources/views/ict-views/request-forms.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'ICT Request Forms')

@section('navbar-badge-icon', 'bi-ticket-perforated-fill')
@section('navbar-badge-label', 'ICT Request Forms')
@section('navbar-back-route', route('unit.ict'))
@section('navbar-back-label', 'Back to ICT Unit')
@section('navbar-action-icon', 'bi-box-arrow-up-right')
@section('navbar-action-label', 'Open ICT Hub')
@section('navbar-action-route', 'https://www.tayabasicthub.com/forms.html')
@section('navbar-action-target', 'target="_blank"')

@section('footer-label', 'ICT Request Forms')
@section('footer-back-route', route('unit.ict'))
@section('footer-back-label', 'Back to ICT Unit')

@section('head')
    <style>
        /* ── Page-specific styles ── */

        /* ── Hero ── */
        .page-hero-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 32px;
        }

        .page-hero-title {
            font-size: clamp(28px, 3.5vw, 44px);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.025em;
            margin-bottom: 12px;
        }

        .page-hero-title .accent {
            color: var(--h-gold);
        }

        .page-hero-desc {
            font-size: 14.5px;
            color: var(--h-text);
            line-height: 1.78;
            max-width: 500px;
        }

        /* Hero count cards */
        .hero-count-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--h-border);
            border-radius: 14px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 210px;
        }

        .hero-count-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.18);
            border: 1px solid rgba(37, 99, 235, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--accent-2);
            flex-shrink: 0;
        }

        .hero-count-num {
            font-size: 26px;
            font-weight: 800;
            color: var(--h-gold);
            line-height: 1;
        }

        .hero-count-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 3px;
        }

        /* ── Progress steps ── */
        .pstep {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            cursor: pointer;
            padding: 6px 0;
        }

        .pstep:not(:last-child)::after {
            content: '';
            display: block;
            width: 40px;
            height: 1px;
            background: var(--border);
            margin: 0 14px;
            flex-shrink: 0;
        }

        .pstep-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--light);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            transition: all .3s;
            flex-shrink: 0;
        }

        .pstep.active .pstep-num {
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .pstep.done .pstep-num {
            background: rgba(5, 150, 105, 0.12);
            border-color: rgba(5, 150, 105, 0.25);
            color: var(--success);
        }

        .pstep-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
        }

        .pstep.active .pstep-label {
            color: var(--accent);
        }

        /* ── Featured banner ── */
        .featured-banner {
            background: linear-gradient(135deg, var(--navy), #0f2d52);
            border-radius: 20px;
            padding: 32px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 44px;
            position: relative;
            overflow: hidden;
        }

        .featured-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15), transparent 65%);
            pointer-events: none;
        }

        .featured-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--h-gold);
            margin-bottom: 10px;
        }

        .featured-title {
            font-size: clamp(17px, 2.2vw, 25px);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .featured-desc {
            font-size: 13px;
            color: var(--h-text);
            line-height: 1.7;
            max-width: 400px;
        }

        .featured-right {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        /* ── Form cards ── */
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 36px;
        }

        .fc {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            padding: 0;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: all .3s;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .fc::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .fc:hover {
            transform: translateY(-6px);
            border-color: transparent;
        }

        .fc:hover::before {
            transform: scaleX(1);
        }

        .fc-blue::before {
            background: linear-gradient(90deg, #1A4A8A, #4d8ef8);
        }

        .fc-green::before {
            background: linear-gradient(90deg, #059669, #34d399);
        }

        .fc-amber::before {
            background: linear-gradient(90deg, #D97706, #fbbf24);
        }

        .fc-violet::before {
            background: linear-gradient(90deg, #7C3AED, #a78bfa);
        }

        .fc-blue:hover {
            box-shadow: 0 24px 56px rgba(26, 74, 138, 0.15);
        }

        .fc-green:hover {
            box-shadow: 0 24px 56px rgba(5, 150, 105, 0.15);
        }

        .fc-amber:hover {
            box-shadow: 0 24px 56px rgba(217, 119, 6, 0.15);
        }

        .fc-violet:hover {
            box-shadow: 0 24px 56px rgba(124, 58, 237, 0.15);
        }

        .fc-top {
            padding: 26px 26px 18px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .fc-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid;
        }

        .fc:hover .fc-icon {
            transform: scale(1.10) rotate(-5deg);
        }

        .fi-blue {
            background: rgba(26, 74, 138, 0.09);
            color: #1A4A8A;
            border-color: rgba(26, 74, 138, 0.18);
        }

        .fi-green {
            background: rgba(5, 150, 105, 0.09);
            color: #059669;
            border-color: rgba(5, 150, 105, 0.18);
        }

        .fi-amber {
            background: rgba(245, 158, 11, 0.09);
            color: #D97706;
            border-color: rgba(245, 158, 11, 0.18);
        }

        .fi-violet {
            background: rgba(124, 58, 237, 0.09);
            color: #7C3AED;
            border-color: rgba(124, 58, 237, 0.18);
        }

        .fc-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 9.5px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            white-space: nowrap;
        }

        .fb-blue {
            background: rgba(26, 74, 138, 0.09);
            color: #1A4A8A;
        }

        .fb-green {
            background: rgba(5, 150, 105, 0.09);
            color: #059669;
        }

        .fb-amber {
            background: rgba(245, 158, 11, 0.09);
            color: #D97706;
        }

        .fb-violet {
            background: rgba(124, 58, 237, 0.09);
            color: #7C3AED;
        }

        .fc-body {
            padding: 0 26px 18px;
            flex: 1;
        }

        .fc-name {
            font-size: 15.5px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 7px;
            letter-spacing: -0.01em;
            line-height: 1.3;
        }

        .fc-desc {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.7;
        }

        .fc-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 0 26px 18px;
        }

        .fct {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 5px;
        }

        .fct-blue {
            background: rgba(26, 74, 138, 0.07);
            color: #1A4A8A;
            border: 1px solid rgba(26, 74, 138, 0.14);
        }

        .fct-green {
            background: rgba(5, 150, 105, 0.07);
            color: #059669;
            border: 1px solid rgba(5, 150, 105, 0.14);
        }

        .fct-amber {
            background: rgba(245, 158, 11, 0.07);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, 0.14);
        }

        .fct-violet {
            background: rgba(124, 58, 237, 0.07);
            color: #7C3AED;
            border: 1px solid rgba(124, 58, 237, 0.14);
        }

        .fc-footer {
            margin: 0 26px 22px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .fc-cta {
            font-size: 12.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: gap .2s;
        }

        .fc:hover .fc-cta {
            gap: 12px;
        }

        .fc-blue .fc-cta {
            color: #1A4A8A;
        }

        .fc-green .fc-cta {
            color: #059669;
        }

        .fc-amber .fc-cta {
            color: #D97706;
        }

        .fc-violet.fc-cta {
            color: #7C3AED;
        }

        .fc-time {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 600;
            color: var(--muted);
            padding: 3px 9px;
            border-radius: 99px;
            background: var(--light);
            border: 1px solid var(--border);
        }

        /* ── How it works (inline) ── */
        .hiw-box {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 36px;
            margin-top: 52px;
        }

        .hiw-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 28px;
        }

        .hiw-step {
            text-align: center;
            padding: 26px 16px;
            border-radius: 14px;
            background: var(--light);
            border: 1px solid var(--border);
            transition: all .3s;
            position: relative;
        }

        .hiw-step:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(11, 31, 58, 0.09);
            background: var(--white);
        }

        .hiw-num {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 6px 16px var(--accent-glow);
        }

        .hiw-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .hiw-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── Sidebar extras ── */
        .office-hours-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
        }

        .office-hours-row:last-child {
            border-bottom: none;
        }

        .oh-day {
            font-weight: 600;
            color: var(--navy);
        }

        .oh-time {
            color: var(--muted);
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            margin-right: 6px;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
            animation: status-pulse 2s infinite;
        }

        @keyframes status-pulse {

            0%,
            100% {
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.06);
            }
        }

        .status-open {
            font-size: 11px;
            font-weight: 700;
            color: #10b981;
        }

        .status-close {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
        }

        .cmi {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
        }

        .cmi:last-child {
            border-bottom: none;
        }

        .cmi-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(26, 74, 138, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--blue);
            flex-shrink: 0;
        }

        .cmi-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .cmi-value {
            font-size: 12px;
            font-weight: 500;
            color: var(--navy);
        }

        /* Modal drawer (for card previews) */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            z-index: 900;
            background: rgba(11, 31, 58, 0.65);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .drawer-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .drawer {
            background: var(--white);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 0 0 32px;
            transform: translateY(100%);
            transition: transform .4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .drawer-overlay.open .drawer {
            transform: translateY(0);
        }

        .drawer-handle {
            width: 40px;
            height: 4px;
            border-radius: 4px;
            background: var(--border);
            margin: 14px auto 0;
            cursor: pointer;
        }

        .drawer-header {
            padding: 20px 28px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .drawer-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            border: 1px solid;
        }

        .drawer-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .drawer-subtitle {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .drawer-close {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--light);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            font-size: 16px;
            transition: all .2s;
        }

        .drawer-close:hover {
            background: #fee2e2;
            color: #ef4444;
            border-color: #fca5a5;
        }

        .drawer-body {
            padding: 24px 28px;
        }

        .drawer-desc {
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.75;
            margin-bottom: 22px;
        }

        .drawer-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .drawer-info-item {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
        }

        .drawer-info-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .drawer-info-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
        }

        .drawer-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
        }

        .drawer-tag {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 6px;
            background: rgba(37, 99, 235, 0.07);
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, 0.14);
        }

        .drawer-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .drawer-btn-primary {
            flex: 1;
            min-width: 160px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px var(--accent-glow);
            transition: all .25s;
        }

        .drawer-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(37, 99, 235, 0.42);
        }

        .drawer-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 20px;
            border-radius: 10px;
            background: var(--light);
            border: 1.5px solid var(--border);
            color: var(--navy);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s;
        }

        .drawer-btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        @media(max-width:640px) {
            .forms-grid {
                grid-template-columns: 1fr;
            }

            .drawer-info-grid {
                grid-template-columns: 1fr;
            }

            .hiw-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:480px) {
            .hiw-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection


@section('content')

    {{-- ── PAGE HERO ── --}}
    <div class="ict-page-hero">
        <div class="ict-hero-grid"></div>
        <div class="ict-hero-orb ict-hero-orb-tr"></div>
        <div class="ict-hero-orb ict-hero-orb-bl"></div>

        <div class="ict-hero-inner">
            <div class="page-hero-inner">
                <div class="ict-a1">
                    <div class="ict-breadcrumb">
                        <a href="{{ route('home') }}">SDO Tayabas City</a>
                        <span class="ict-breadcrumb-sep">/</span>
                        <a href="{{ route('unit.ict') }}">ICT Unit</a>
                        <span class="ict-breadcrumb-sep">/</span>
                        <span>Request Forms</span>
                    </div>
                    <h1 class="page-hero-title">
                        ICT <span class="accent">Request Forms</span><br>& Online Ticketing
                    </h1>
                    <p class="page-hero-desc">
                        All official ICT forms in one place. Choose the form that matches your concern
                        and submit through the Tayabas ICT Hub — fast, trackable, and paperless.
                    </p>
                    <div class="ict-meta-pills">
                        <span class="ict-meta-pill"><i class="bi bi-shield-check-fill"></i> Official DepEd Forms</span>
                        <span class="ict-meta-pill"><i class="bi bi-lightning-fill"></i> Fast Response</span>
                        <span class="ict-meta-pill"><i class="bi bi-eye-fill"></i> Ticket Tracking</span>
                    </div>
                </div>

                <div class="ict-a2" style="display:flex;flex-direction:column;gap:12px;flex-shrink:0;">
                    <div class="hero-count-card">
                        <div class="hero-count-icon"><i class="bi bi-file-earmark-fill"></i></div>
                        <div>
                            <div class="hero-count-num">4</div>
                            <div class="hero-count-lbl">Available Forms</div>
                        </div>
                    </div>
                    <div class="hero-count-card">
                        <div class="hero-count-icon"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="hero-count-num">24h</div>
                            <div class="hero-count-lbl">Avg. Response Time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── PROGRESS STEPS ── --}}
    <div class="ict-progress-wrap">
        <div class="ict-progress-steps" id="progressSteps">
            @foreach (['Choose a Form', 'Fill Out Details', 'Submit Ticket', 'ICT Reviews', 'Resolution'] as $i => $label)
                <div class="pstep {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i }}">
                    <div class="pstep-num">{{ $i + 1 }}</div>
                    <span class="pstep-label">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ── MAIN ── --}}
    <div class="ict-page-body">

        {{-- Featured Banner --}}
        <div class="featured-banner ict-reveal">
            <div style="position:relative;z-index:1;">
                <div class="featured-eyebrow"><i class="bi bi-star-fill"></i> Official Portal</div>
                <div class="featured-title">Submit Tickets at Tayabas ICT Hub</div>
                <div class="featured-desc">
                    All forms below route to <strong style="color:#fff;">tayabasicthub.com</strong> — available 24/7 for
                    all DepEd Tayabas City personnel.
                </div>
            </div>
            <div class="featured-right">
                <a href="https://www.tayabasicthub.com/forms.html" class="ict-btn-primary" target="_blank">
                    <i class="bi bi-ticket-perforated-fill"></i> Go to ICT Hub
                </a>
                <a href="#how-it-works" class="ict-btn-outline-light">
                    <i class="bi bi-info-circle"></i> How It Works
                </a>
            </div>
        </div>

        {{-- Two-column layout --}}
        <div class="ict-two-col">

            {{-- ── LEFT: FORM CARDS ── --}}
            <div>
                <div class="ict-reveal">
                    <div class="ict-section-label">Official Forms</div>
                    <h2 class="ict-section-title">Choose Your Request Form</h2>
                    <p class="ict-section-desc">
                        Select the category that best matches your concern. Each card opens a quick
                        overview — then takes you straight to the form.
                    </p>
                </div>

                <div class="forms-grid">

                    {{-- Technical Assistance --}}
                    <div class="fc fc-blue ict-reveal ict-d1" onclick="openDrawer('tech')">
                        <div class="fc-top">
                            <div class="fc-icon fi-blue"><i class="bi bi-tools"></i></div>
                            <span class="fc-badge fb-blue"><i class="bi bi-circle-fill" style="font-size:6px;"></i> Most
                                Common</span>
                        </div>
                        <div class="fc-body">
                            <div class="fc-name">ICT Technical Assistance Form</div>
                            <div class="fc-desc">For hardware issues, software problems, network concerns, device repairs,
                                and general ICT troubleshooting across all schools and offices.</div>
                        </div>
                        <div class="fc-tags">
                            <span class="fct fct-blue">Hardware Repair</span>
                            <span class="fct fct-blue">Network Issues</span>
                            <span class="fct fct-blue">Software</span>
                            <span class="fct fct-blue">Printer</span>
                        </div>
                        <div class="fc-footer">
                            <span class="fc-cta">Fill Out Form <i class="bi bi-arrow-right"></i></span>
                            <span class="fc-time"><i class="bi bi-clock"></i> ~5 min</span>
                        </div>
                    </div>

                    {{-- DTS --}}
                    <div class="fc fc-green ict-reveal ict-d2" onclick="openDrawer('dts')">
                        <div class="fc-top">
                            <div class="fc-icon fi-green"><i class="bi bi-file-earmark-text-fill"></i></div>
                            <span class="fc-badge fb-green"><i class="bi bi-circle-fill" style="font-size:6px;"></i>
                                Documents</span>
                        </div>
                        <div class="fc-body">
                            <div class="fc-name">DTS Request Form</div>
                            <div class="fc-desc">For document tracking and processing — retrieval, editing, or cancellation
                                of official documents through the Division's DTS.</div>
                        </div>
                        <div class="fc-tags">
                            <span class="fct fct-green">Doc Retrieval</span>
                            <span class="fct fct-green">DTS Editing</span>
                            <span class="fct fct-green">Cancellation</span>
                        </div>
                        <div class="fc-footer">
                            <span class="fc-cta">Fill Out Form <i class="bi bi-arrow-right"></i></span>
                            <span class="fc-time"><i class="bi bi-clock"></i> ~3 min</span>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="fc fc-amber ict-reveal ict-d3" onclick="openDrawer('email')">
                        <div class="fc-top">
                            <div class="fc-icon fi-amber"><i class="bi bi-envelope-fill"></i></div>
                            <span class="fc-badge fb-amber"><i class="bi bi-circle-fill" style="font-size:6px;"></i>
                                Accounts</span>
                        </div>
                        <div class="fc-body">
                            <div class="fc-name">Email Request Form</div>
                            <div class="fc-desc">For DepEd official email account creation, password resets, account
                                recovery, and other email service requests.</div>
                        </div>
                        <div class="fc-tags">
                            <span class="fct fct-amber">Account Creation</span>
                            <span class="fct fct-amber">Password Reset</span>
                            <span class="fct fct-amber">Recovery</span>
                        </div>
                        <div class="fc-footer">
                            <span class="fc-cta">Fill Out Form <i class="bi bi-arrow-right"></i></span>
                            <span class="fc-time"><i class="bi bi-clock"></i> ~3 min</span>
                        </div>
                    </div>

                    {{-- Help Desk --}}
                    <div class="fc fc-violet ict-reveal ict-d4" onclick="openDrawer('helpdesk')">
                        <div class="fc-top">
                            <div class="fc-icon fi-violet"><i class="bi bi-headset"></i></div>
                            <span class="fc-badge fb-violet"><i class="bi bi-circle-fill" style="font-size:6px;"></i>
                                General</span>
                        </div>
                        <div class="fc-body">
                            <div class="fc-name">Help Desk Form</div>
                            <div class="fc-desc">General helpdesk requests and support needs not covered by the other
                                categories. Submit any IT concern for evaluation.</div>
                        </div>
                        <div class="fc-tags">
                            <span class="fct fct-violet">General IT</span>
                            <span class="fct fct-violet">Inquiry</span>
                            <span class="fct fct-violet">Other Concerns</span>
                        </div>
                        <div class="fc-footer">
                            <span class="fc-cta">Fill Out Form <i class="bi bi-arrow-right"></i></span>
                            <span class="fc-time"><i class="bi bi-clock"></i> ~4 min</span>
                        </div>
                    </div>

                </div>{{-- /forms-grid --}}

                {{-- How It Works --}}
                <div class="hiw-box ict-reveal" id="how-it-works">
                    <div class="ict-section-label">Step-by-Step</div>
                    <h2 class="ict-section-title">How the Ticketing Process Works</h2>
                    <p class="ict-section-desc">Four simple steps from submission to resolution.</p>
                    <div class="hiw-grid">
                        @foreach ([['1', 'Choose & Fill', 'Pick the right form and provide complete details — issue type, device, school, and description.'], ['2', 'Submit Online', 'Submit at tayabasicthub.com and receive an email confirmation with your ticket reference number.'], ['3', 'ICT Assigns', 'The ICT Unit reviews and assigns your ticket to the appropriate technician based on the issue.'], ['4', 'Resolved ✓', 'The technician contacts you, resolves the concern, and collects confirmation and feedback.']] as [$num, $title, $desc])
                            <div class="hiw-step">
                                <div class="hiw-num">{{ $num }}</div>
                                <div class="hiw-title">{{ $title }}</div>
                                <div class="hiw-desc">{{ $desc }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>{{-- /left col --}}

            {{-- ── RIGHT: SIDEBAR ── --}}
            <div class="ict-sidebar">

                {{-- Quick Tips --}}
                <div class="ict-sidebar-card ict-reveal ict-d1">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-lightbulb-fill"></i></div>
                        <div class="ict-sidebar-card-title">Quick Tips</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Choose the most specific form for
                            faster assistance.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Include your school name, room, and
                            device model for hardware issues.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Use your official DepEd email for
                            faster verification.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Keep your ticket reference number to
                            follow up on your request.</div>
                    </div>
                </div>

                {{-- Office Hours --}}
                <div class="ict-sidebar-card ict-reveal ict-d2">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-clock-fill"></i></div>
                        <div class="ict-sidebar-card-title">Office Hours</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="office-hours-row"><span class="oh-day">Monday – Friday</span><span
                                class="oh-time">8:00 AM – 5:00 PM</span></div>
                        <div class="office-hours-row"><span class="oh-day">Saturday</span><span
                                class="oh-time">Closed</span></div>
                        <div class="office-hours-row"><span class="oh-day">Sunday</span><span
                                class="oh-time">Closed</span></div>
                        <div style="padding-top:12px;font-size:12px;color:var(--muted);">
                            <span class="status-dot" id="statusDot"></span>
                            <span id="statusText" class="status-open">Checking…</span>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="ict-sidebar-card ict-reveal ict-d3">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-person-lines-fill"></i></div>
                        <div class="ict-sidebar-card-title">ICT Unit Contact</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="cmi">
                            <div class="cmi-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <div class="cmi-label">Email</div>
                                <div class="cmi-value">ict.tayabas.city@deped.gov.ph</div>
                            </div>
                        </div>
                        <div class="cmi">
                            <div class="cmi-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="cmi-label">Location</div>
                                <div class="cmi-value">ICT Unit, SDO Tayabas City</div>
                            </div>
                        </div>
                        <div class="cmi">
                            <div class="cmi-icon"><i class="bi bi-globe2"></i></div>
                            <div>
                                <div class="cmi-label">Portal</div>
                                <div class="cmi-value">tayabasicthub.com</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Back to ICT --}}
                <a href="{{ route('unit.ict') }}" class="ict-sidebar-card ict-reveal ict-d4"
                    style="text-decoration:none;display:flex;align-items:center;gap:14px;padding:18px 20px;transition:all .25s;"
                    onmouseenter="this.style.background='var(--light)'" onmouseleave="this.style.background=''">
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(26,74,138,0.08);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--blue);font-size:16px;flex-shrink:0;">
                        <i class="bi bi-arrow-left-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy);">Back to ICT Unit</div>
                        <div style="font-size:11px;color:var(--muted);">View all services &amp; announcements</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:var(--muted);margin-left:auto;font-size:13px;"></i>
                </a>

            </div>{{-- /sidebar --}}
        </div>{{-- /two-col --}}
    </div>{{-- /page-body --}}


    {{-- ── CARD PREVIEW DRAWER ── --}}
    <div class="drawer-overlay" id="drawerOverlay" onclick="handleDrawerOverlayClick(event)">
        <div class="drawer">
            <div class="drawer-handle" onclick="closeDrawer()"></div>
            <div class="drawer-header">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div class="drawer-icon" id="drawerIcon"></div>
                    <div>
                        <div class="drawer-title" id="drawerTitle"></div>
                        <div class="drawer-subtitle" id="drawerSubtitle"></div>
                    </div>
                </div>
                <div class="drawer-close" onclick="closeDrawer()"><i class="bi bi-x-lg"></i></div>
            </div>
            <div class="drawer-body">
                <p class="drawer-desc" id="drawerDesc"></p>
                <div class="drawer-info-grid" id="drawerInfoGrid"></div>
                <div class="drawer-tags" id="drawerTags"></div>
                <div class="drawer-actions">
                    <a href="#" class="drawer-btn-primary" id="drawerPrimaryBtn">
                        <i class="bi bi-ticket-perforated-fill"></i> Fill Out This Form
                    </a>
                    <span class="drawer-btn-secondary" onclick="closeDrawer()">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        /* ── Progress steps ── */
        const pSteps = document.querySelectorAll('.pstep');
        pSteps.forEach((step, i) => {
            step.addEventListener('click', () => {
                pSteps.forEach((s, j) => {
                    s.classList.remove('active', 'done');
                    if (j < i) s.classList.add('done');
                    if (j === i) s.classList.add('active');
                });
            });
        });

        /* ── Office hours status ── */
        function checkStatus() {
            const now = new Date(),
                day = now.getDay(),
                h = now.getHours();
            const open = day >= 1 && day <= 5 && h >= 8 && h < 17;
            const dot = document.getElementById('statusDot');
            const txt = document.getElementById('statusText');
            if (!open) {
                dot.style.background = '#9ca3af';
                dot.style.boxShadow = '0 0 0 3px rgba(156,163,175,0.18)';
                dot.style.animation = 'none';
                txt.textContent = 'ICT Unit is currently CLOSED';
                txt.className = 'status-close';
            } else {
                txt.textContent = 'ICT Unit is currently OPEN';
            }
        }
        checkStatus();

        /* ── Drawer data ── */
        const drawerData = {
            tech: {
                icon: '<i class="bi bi-tools"></i>',
                iconClass: 'fi-blue',
                title: 'ICT Technical Assistance Form',
                subtitle: 'Hardware, Software & Network Issues',
                desc: 'Use this form to report any technical problem involving your device, school network, software, or ICT equipment. The most commonly used form for day-to-day technical concerns.',
                href: '{{ route('ict.ta-form') }}',
                info: [{
                    label: 'Response Time',
                    value: 'Within 24 hours'
                }, {
                    label: 'Form Length',
                    value: '~5 minutes'
                }, {
                    label: 'Who Can Submit',
                    value: 'All DepEd Personnel'
                }, {
                    label: 'Availability',
                    value: '24/7 Online'
                }],
                tags: ['Hardware Repair', 'Network Issues', 'Software Installation', 'Printer/Projector',
                    'OS Reinstallation', 'Internet Connectivity'
                ],
            },
            dts: {
                icon: '<i class="bi bi-file-earmark-text-fill"></i>',
                iconClass: 'fi-green',
                title: 'DTS Request Form',
                subtitle: 'Document Tracking System Requests',
                desc: 'Use this form to request any action on official documents in the Division\'s Document Tracking System — retrieval, editing, or cancellation.',
                href: '{{ route('ict.dts-form') }}',
                info: [{
                    label: 'Response Time',
                    value: 'Within 1 business day'
                }, {
                    label: 'Form Length',
                    value: '~3 minutes'
                }, {
                    label: 'Who Can Submit',
                    value: 'Authorized Personnel'
                }, {
                    label: 'Availability',
                    value: '24/7 Online'
                }],
                tags: ['Document Retrieval', 'DTS Editing', 'Document Cancellation', 'Records Management'],
            },
            email: {
                icon: '<i class="bi bi-envelope-fill"></i>',
                iconClass: 'fi-amber',
                title: 'Email Request Form',
                subtitle: 'DepEd Email Account Services',
                desc: 'Use this form to request the creation of a new official DepEd email account, reset a password, recover access, or report any email-related issue.',
                href: '{{ route('ict.email-form') }}',
                info: [{
                    label: 'Response Time',
                    value: 'Within 24–48 hours'
                }, {
                    label: 'Form Length',
                    value: '~3 minutes'
                }, {
                    label: 'Who Can Submit',
                    value: 'All DepEd Personnel'
                }, {
                    label: 'Availability',
                    value: '24/7 Online'
                }],
                tags: ['Account Creation', 'Password Reset', 'Account Recovery', 'Email Access Issues',
                    'Google Workspace'
                ],
            },
            helpdesk: {
                icon: '<i class="bi bi-headset"></i>',
                iconClass: 'fi-violet',
                title: 'Help Desk Form',
                subtitle: 'General IT Concerns & Inquiries',
                desc: 'Use this form for general IT concerns, questions, or support requests that don\'t fit the other categories. The ICT Unit will evaluate and route your submission.',
                href: '{{ route('ict.helpdesk-form') }}',
                info: [{
                    label: 'Response Time',
                    value: 'Within 1–2 business days'
                }, {
                    label: 'Form Length',
                    value: '~4 minutes'
                }, {
                    label: 'Who Can Submit',
                    value: 'All DepEd Personnel'
                }, {
                    label: 'Availability',
                    value: '24/7 Online'
                }],
                tags: ['General Inquiry', 'Other IT Concerns', 'System Access', 'Advisory'],
            },
        };

        function openDrawer(type) {
            const d = drawerData[type];
            if (!d) return;

            const icon = document.getElementById('drawerIcon');
            icon.innerHTML = d.icon;
            icon.className = 'drawer-icon ' + d.iconClass;
            document.getElementById('drawerTitle').textContent = d.title;
            document.getElementById('drawerSubtitle').textContent = d.subtitle;
            document.getElementById('drawerDesc').textContent = d.desc;
            document.getElementById('drawerInfoGrid').innerHTML = d.info.map(i =>
                `<div class="drawer-info-item"><div class="drawer-info-label">${i.label}</div><div class="drawer-info-value">${i.value}</div></div>`
            ).join('');
            document.getElementById('drawerTags').innerHTML = d.tags.map(t =>
                `<span class="drawer-tag">${t}</span>`
            ).join('');
            const btn = document.getElementById('drawerPrimaryBtn');
            btn.href = d.href;
            // external links open in new tab
            btn.target = d.href.startsWith('http') ? '_blank' : '_self';

            document.getElementById('drawerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';

            // advance progress to step 2
            pSteps.forEach((s, j) => {
                s.classList.remove('active', 'done');
                if (j === 0) s.classList.add('done');
                if (j === 1) s.classList.add('active');
            });
        }

        function closeDrawer() {
            document.getElementById('drawerOverlay').classList.remove('open');
            document.body.style.overflow = '';
            pSteps.forEach((s, j) => {
                s.classList.remove('active', 'done');
                if (j === 0) s.classList.add('active');
            });
        }

        function handleDrawerOverlayClick(e) {
            if (e.target === document.getElementById('drawerOverlay')) closeDrawer();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeDrawer();
        });
    </script>
@endsection
