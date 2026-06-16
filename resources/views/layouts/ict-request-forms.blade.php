{{--
    ┌────────────────────────────────────────────────────────────────────────┐
    │  LAYOUT: resources/views/layouts/ict-request-forms.blade.php          │
    │                                                                        │
    │  Usage in child views:                                                 │
    │                                                                        │
    │  @extends('layouts.ict-request-forms')                                │
    │                                                                        │
    │  @section('title', 'Page Title')                                      │
    │  @section('navbar-badge-icon', 'bi-tools')                            │
    │  @section('navbar-badge-label', 'Technical Assistance Form')          │
    │  @section('navbar-back-route', route('ict.forms'))                    │
    │  @section('navbar-back-label', 'Back to Forms')                       │
    │                                                                        │
    │  @section('content')                                                  │
    │      ... your page content here ...                                   │
    │  @endsection                                                          │
    │                                                                        │
    │  Optional sections:                                                    │
    │  @section('head')          — extra <head> tags / styles              │
    │  @section('footer-label', 'Page Label')                              │
    │  @section('footer-back-route', route('ict.forms'))                   │
    │  @section('footer-back-label', 'Back to Forms')                      │
    │  @section('scripts')       — extra scripts before </body>            │
    └────────────────────────────────────────────────────────────────────────┘
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ICT Unit') — SDO Tayabas City</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        /* ════════════════════════════════════════════════════
           SHARED DESIGN TOKENS  (all ICT form pages inherit)
           ════════════════════════════════════════════════════ */
        :root {
            --navy: #0B1F3A;
            --blue: #1A4A8A;
            --light: #F4F7FB;
            --white: #FFFFFF;
            --muted: #6B7A90;
            --border: rgba(26, 74, 138, 0.12);
            --h-border: rgba(255, 255, 255, 0.09);
            --h-text: rgba(255, 255, 255, 0.60);
            --h-gold: #f0c040;
            --accent: #2563eb;
            --accent-2: #4d8ef8;
            --accent-glow: rgba(37, 99, 235, 0.22);
            --success: #059669;
            --danger: #dc2626;
            --warning: #d97706;
            --radius: 16px;
            --radius-sm: 10px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light);
            color: var(--navy);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ════════ NAVBAR ════════ */
        #ict-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 500;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-bottom: 1px solid rgba(26, 74, 138, 0.08);
            transition: box-shadow .3s;
        }

        #ict-navbar.scrolled {
            box-shadow: 0 4px 24px rgba(11, 31, 58, 0.10);
        }

        /* Brand */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-brand-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .nav-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .nav-brand-text strong {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            margin-top: 15px;
            line-height: 0;
        }

        .nav-brand-text span {
            font-size: 10.5px;
            color: var(--muted);
            font-weight: 400;
        }

        /* Center cluster */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 14px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-unit-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 99px;
            background: rgba(26, 74, 138, 0.08);
            border: 1px solid rgba(26, 74, 138, 0.15);
            font-size: 12px;
            font-weight: 600;
            color: var(--blue);
            white-space: nowrap;
        }

        .nav-divider {
            width: 1px;
            height: 18px;
            background: rgba(26, 74, 138, 0.15);
            flex-shrink: 0;
        }

        .nav-back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            transition: color .2s;
            white-space: nowrap;
        }

        .nav-back-link:hover {
            color: var(--navy);
        }

        /* Right button */
        .btn-nav {
            padding: 9px 22px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 14px rgba(26, 74, 138, 0.28);
            transition: all 0.2s;
            cursor: pointer;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .btn-nav:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 74, 138, 0.40);
        }

        @media(max-width:900px) {
            .nav-center {
                display: none;
            }
        }

        @media(max-width:560px) {
            .btn-nav span {
                display: none;
            }

            .btn-nav {
                padding: 9px 14px;
            }
        }

        /* ════════ SHARED UTILITY CLASSES ════════ */

        /* Breadcrumb */
        .ict-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(240, 192, 64, 0.85);
            margin-bottom: 16px;
        }

        .ict-breadcrumb a {
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            transition: color .2s;
        }

        .ict-breadcrumb a:hover {
            color: #fff;
        }

        .ict-breadcrumb-sep {
            color: rgba(255, 255, 255, 0.2);
        }

        /* Page hero background (dark) */
        .ict-page-hero {
            padding: 100px 6% 48px;
            background: linear-gradient(135deg, var(--navy) 0%, #0f2d52 55%, #0B1F3A 100%);
            position: relative;
            overflow: hidden;
        }

        .ict-hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .ict-hero-orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.13) 0%, transparent 65%);
            pointer-events: none;
        }

        .ict-hero-orb-tr {
            top: -80px;
            right: -80px;
            width: 400px;
            height: 400px;
        }

        .ict-hero-orb-bl {
            bottom: -80px;
            left: -40px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(240, 192, 64, 0.07) 0%, transparent 65%);
        }

        .ict-hero-inner {
            position: relative;
            z-index: 2;
        }

        /* Progress steps bar */
        .ict-progress-wrap {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 6%;
            position: sticky;
            top: 70px;
            z-index: 400;
        }

        .ict-progress-steps {
            display: flex;
            align-items: center;
            overflow-x: auto;
            scrollbar-width: none;
            padding: 14px 0;
        }

        .ict-progress-steps::-webkit-scrollbar {
            display: none;
        }

        .ict-pstep {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .ict-pstep:not(:last-child)::after {
            content: '';
            display: block;
            width: 32px;
            height: 1px;
            background: var(--border);
            margin: 0 10px;
        }

        .ict-pstep-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--light);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--muted);
            transition: all .3s;
            flex-shrink: 0;
        }

        .ict-pstep.active .ict-pstep-num {
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .ict-pstep.done .ict-pstep-num {
            background: rgba(5, 150, 105, 0.12);
            border-color: rgba(5, 150, 105, 0.25);
            color: var(--success);
            font-size: 0;
        }

        .ict-pstep.done .ict-pstep-num::after {
            content: '✓';
            font-size: 11px;
        }

        .ict-pstep-label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
        }

        .ict-pstep.active .ict-pstep-label {
            color: var(--accent);
        }

        .ict-pstep.done .ict-pstep-label {
            color: var(--success);
        }

        /* Section label */
        .ict-section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
        }

        .ict-section-label::before {
            content: '';
            display: block;
            width: 22px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .ict-section-title {
            font-size: clamp(22px, 2.8vw, 34px);
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
        }

        .ict-section-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.78;
            max-width: 540px;
        }

        /* Two-column layout (main + sidebar) */
        .ict-two-col {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 28px;
            align-items: start;
        }

        @media(max-width:960px) {
            .ict-two-col {
                grid-template-columns: 1fr;
            }

            .ict-sidebar {
                order: -1;
            }
        }

        /* Sidebar card */
        .ict-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .ict-sidebar-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .ict-sidebar-card-header {
            padding: 14px 20px;
            background: linear-gradient(135deg, rgba(26, 74, 138, 0.05), rgba(37, 99, 235, 0.03));
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ict-sidebar-header-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(37, 99, 235, 0.10);
            border: 1px solid rgba(37, 99, 235, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--accent);
        }

        .ict-sidebar-card-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
        }

        .ict-sidebar-card-body {
            padding: 14px 20px;
        }

        /* Sidebar tip item */
        .ict-tip {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        .ict-tip:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .ict-tip i {
            color: var(--accent);
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Sidebar info row */
        .ict-info-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
        }

        .ict-info-row:last-child {
            border-bottom: none;
        }

        .ict-info-row-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: rgba(26, 74, 138, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--blue);
            flex-shrink: 0;
        }

        .ict-info-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .ict-info-value {
            font-size: 12px;
            font-weight: 500;
            color: var(--navy);
            margin-top: 1px;
        }

        /* Form card wrapper */
        .ict-form-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(11, 31, 58, 0.06);
        }

        .ict-form-card-header {
            padding: 22px 32px 18px;
            background: linear-gradient(135deg, rgba(26, 74, 138, 0.05), rgba(37, 99, 235, 0.03));
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .ict-form-card-header-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(26, 74, 138, 0.10);
            border: 1px solid rgba(26, 74, 138, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--blue);
            flex-shrink: 0;
        }

        .ict-form-card-header-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .ict-form-card-header-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px;
        }

        .ict-form-body {
            padding: 32px;
        }

        /* Form section divider */
        .ict-form-section {
            margin-bottom: 32px;
        }

        .ict-form-section:last-child {
            margin-bottom: 0;
        }

        .ict-form-section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        /* Field groups */
        .ict-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .ict-field-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 18px;
        }

        @media(max-width:640px) {

            .ict-field-row,
            .ict-field-row-3 {
                grid-template-columns: 1fr;
            }
        }

        .ict-f-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 0;
        }

        .ict-f-group label {
            font-size: 11px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .ict-f-group label .req {
            color: var(--danger);
            font-size: 13px;
            line-height: 1;
        }

        .ict-f-group input[type="text"],
        .ict-f-group input[type="date"],
        .ict-f-group input[type="email"],
        .ict-f-group input[type="number"],
        .ict-f-group input[type="tel"],
        .ict-f-group select,
        .ict-f-group textarea {
            width: 100%;
            padding: 11px 15px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-family: 'Poppins', sans-serif;
            color: var(--navy);
            background: #FAFBFD;
            transition: all .2s;
            outline: none;
            -webkit-appearance: none;
        }

        .ict-f-group input:focus,
        .ict-f-group select:focus,
        .ict-f-group textarea:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .ict-f-group input.error,
        .ict-f-group select.error,
        .ict-f-group textarea.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
        }

        .ict-f-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .ict-field-hint {
            font-size: 11px;
            color: var(--muted);
        }

        .ict-field-error {
            font-size: 11px;
            color: var(--danger);
            display: none;
        }

        /* Replace the existing block */
        .ict-input-icon {
            position: relative;
            display: block;
        }

        .ict-input-icon>i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
            z-index: 1;
            line-height: 1;
        }

        .ict-input-icon input,
        .ict-input-icon select {
            padding-left: 35px !important;
        }

        /* Checkbox grid */
        .ict-check-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
        }

        .ict-check-opt {
            position: relative;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all .2s;
            background: #FAFBFD;
            user-select: none;
        }

        .ict-check-opt:hover {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.03);
        }

        .ict-check-opt.checked {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.06);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .ict-check-opt input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .ict-check-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--navy);
            pointer-events: none;
        }

        .ict-check-box {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 2px solid var(--border);
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .2s;
        }

        .ict-check-opt.checked .ict-check-box {
            background: var(--accent);
            border-color: var(--accent);
        }

        .ict-check-box i {
            font-size: 11px;
            color: #fff;
            opacity: 0;
            transition: opacity .15s;
        }

        .ict-check-opt.checked .ict-check-box i {
            opacity: 1;
        }

        /* Form actions bar */
        .ict-form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 22px 32px;
            border-top: 1px solid var(--border);
            background: var(--light);
        }

        .ict-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 22px;
            border-radius: var(--radius-sm);
            background: var(--white);
            border: 1.5px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
        }

        .ict-btn-cancel:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: rgba(220, 38, 38, 0.04);
        }

        .ict-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 32px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            border: none;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 22px var(--accent-glow);
            transition: all .25s;
        }

        .ict-btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.42);
        }

        .ict-btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .ict-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: ict-spin .6s linear infinite;
            display: none;
        }

        .ict-btn-submit.loading .ict-spinner {
            display: block;
        }

        .ict-btn-submit.loading .ict-btn-text {
            display: none;
        }

        @keyframes ict-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .ict-required-notice {
            font-size: 11.5px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .ict-required-notice .req {
            color: var(--danger);
            font-size: 14px;
        }

        /* Buttons */
        .ict-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px var(--accent-glow);
            transition: all .25s;
            border: none;
            cursor: pointer;
        }

        .ict-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.40);
        }

        .ict-btn-outline-light {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: rgba(255, 255, 255, 0.80);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all .25s;
        }

        .ict-btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.13);
            color: #fff;
        }

        /* Toast notification */
        .ict-toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 16px 40px rgba(11, 31, 58, 0.14);
            transform: translateY(120%);
            transition: transform .4s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-width: 360px;
            pointer-events: none;
        }

        .ict-toast.show {
            transform: translateY(0);
            pointer-events: all;
        }

        .ict-toast-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .ict-toast-icon.success {
            background: rgba(5, 150, 105, 0.10);
            color: var(--success);
        }

        .ict-toast-icon.error {
            background: rgba(220, 38, 38, 0.10);
            color: var(--danger);
        }

        .ict-toast-icon.warning {
            background: rgba(217, 119, 6, 0.10);
            color: var(--warning);
        }

        .ict-toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 2px;
        }

        .ict-toast-msg {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }

        .ict-toast-close {
            margin-left: auto;
            padding: 4px;
            cursor: pointer;
            color: var(--muted);
            font-size: 16px;
            flex-shrink: 0;
            transition: color .2s;
        }

        .ict-toast-close:hover {
            color: var(--navy);
        }

        /* Confirm modal */
        .ict-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 800;
            background: rgba(11, 31, 58, 0.55);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
            padding: 20px;
        }

        .ict-modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .ict-modal-box {
            background: var(--white);
            border-radius: 20px;
            padding: 36px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            transform: scale(0.92) translateY(16px);
            transition: transform .35s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 24px 64px rgba(11, 31, 58, 0.18);
        }

        .ict-modal-overlay.open .ict-modal-box {
            transform: scale(1) translateY(0);
        }

        .ict-modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(37, 99, 235, 0.10);
            border: 1px solid rgba(37, 99, 235, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--accent);
            margin: 0 auto 20px;
        }

        .ict-modal-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .ict-modal-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.75;
            margin-bottom: 22px;
        }

        .ict-modal-summary {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            margin-bottom: 22px;
        }

        .ict-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
        }

        .ict-summary-row:last-child {
            border-bottom: none;
        }

        .ict-summary-label {
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.06em;
            white-space: nowrap;
        }

        .ict-summary-value {
            color: var(--navy);
            font-weight: 500;
            text-align: right;
        }

        .ict-modal-actions {
            display: flex;
            gap: 12px;
        }

        .ict-modal-btn-cancel {
            flex: 1;
            padding: 12px;
            border-radius: var(--radius-sm);
            background: var(--light);
            border: 1.5px solid var(--border);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: var(--muted);
            cursor: pointer;
            transition: all .2s;
        }

        .ict-modal-btn-cancel:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        .ict-modal-btn-confirm {
            flex: 1;
            padding: 12px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            border: none;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 14px var(--accent-glow);
            transition: all .25s;
        }

        .ict-modal-btn-confirm:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.38);
        }

        /* Hero meta pills */
        .ict-meta-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }

        .ict-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
        }

        .ict-meta-pill i {
            color: var(--accent-2);
        }

        /* Page body */
        .ict-page-body {
            padding: 44px 6% 80px;
        }

        /* Reveal animation */
        .ict-reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .ict-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .ict-d1 {
            transition-delay: .08s;
        }

        .ict-d2 {
            transition-delay: .18s;
        }

        .ict-d3 {
            transition-delay: .28s;
        }

        .ict-d4 {
            transition-delay: .38s;
        }

        @keyframes ict-fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ict-a1 {
            opacity: 0;
            animation: ict-fadeUp .75s ease .10s forwards;
        }

        .ict-a2 {
            opacity: 0;
            animation: ict-fadeUp .75s ease .22s forwards;
        }

        .ict-a3 {
            opacity: 0;
            animation: ict-fadeUp .75s ease .34s forwards;
        }

        .ict-a4 {
            opacity: 0;
            animation: ict-fadeUp .75s ease .46s forwards;
        }

        /* ════════ FOOTER ════════ */
        .ict-footer {
            background: var(--navy);
            padding: 28px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .ict-footer-left strong {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.70);
            margin-bottom: 2px;
        }

        .ict-footer-left p {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.38);
        }

        .ict-footer-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.55);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: all .2s;
        }

        .ict-footer-back:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        @media(max-width:640px) {
            .ict-page-body {
                padding: 28px 5% 60px;
            }
        }
    </style>
    @yield('head')
</head>

<body>

    <!-- ─── NAVBAR ─── -->
    <nav id="ict-navbar">
        <a href="{{ route('home') }}" class="nav-brand">
            <div class="nav-brand-icon">
                <img src="{{ asset('storage/logo-nav.png') }}" alt="SDO Logo" onload="this.style.display='block'"
                    onerror="this.style.display='none'">
            </div>
            <div class="nav-brand-text">
                <strong>SDO Tayabas City</strong>
                <span>Schools Division Office</span>
            </div>
        </a>

        <div class="nav-center">
            <span class="nav-unit-badge">
                <i class="bi @yield('navbar-badge-icon', 'bi-cpu-fill')"></i>
                @yield('navbar-badge-label', 'ICT Unit')
            </span>
            <div class="nav-divider"></div>
            <a href="@yield('navbar-back-route', route('unit.ict'))" class="nav-back-link">
                <i class="bi bi-chevron-left"></i>
                @yield('navbar-back-label', 'Back to ICT Unit')
            </a>
        </div>

        {{-- Right side — auth aware --}}
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            @auth
                {{-- Dashboard button based on role --}}
                <a href="{{ route('ict.admin.dashboard') }}" class="btn-nav"
                    style="background:rgba(26,74,138,0.10);color:var(--blue);border:1.5px solid rgba(26,74,138,0.18);box-shadow:none;">
                    <i class="bi bi-speedometer2"></i>
                    <span>My Dashboard</span>
                </a>

                {{-- Original action button --}}
                <a href="@yield('navbar-action-route', route('ict.forms'))" class="btn-nav" @yield('navbar-action-target')>
                    <i class="bi @yield('navbar-action-icon', 'bi-ticket-perforated-fill')"></i>
                    <span>@yield('navbar-action-label', 'All Forms')</span>
                </a>
            @else
                {{-- Not logged in — show login button --}}
                <a href="{{ route('login') }}" target="_blank" class="btn-nav">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </a>
            @endauth
        </div>
    </nav>


    <!-- ─── PAGE CONTENT ─── -->
    @yield('content')


    <!-- ─── SHARED TOAST ─── -->
    <div class="ict-toast" id="ict-toast">
        <div class="ict-toast-icon" id="ict-toast-icon"></div>
        <div>
            <div class="ict-toast-title" id="ict-toast-title"></div>
            <div class="ict-toast-msg" id="ict-toast-msg"></div>
        </div>
        <div class="ict-toast-close" onclick="ictHideToast()"><i class="bi bi-x-lg"></i></div>
    </div>


    <!-- ─── FOOTER ─── -->
    <div class="ict-footer">
        <div class="ict-footer-left">
            <strong>@yield('footer-label', 'ICT Unit') — SDO Tayabas City</strong>
            <p>© {{ date('Y') }} Schools Division Office of Tayabas City &nbsp;·&nbsp; DepEd Philippines
                &nbsp;·&nbsp; Developed by Mark Bryan F. Valencia &amp; San Mark A. Morcoso</p>
        </div>
        <a href="@yield('footer-back-route', route('unit.ict'))" class="ict-footer-back">
            <i class="bi bi-arrow-left"></i>
            @yield('footer-back-label', 'Back to ICT Unit')
        </a>
    </div>


    <!-- ─── SHARED JS HELPERS ─── -->
    <script>
        /* Navbar scroll shadow */
        window.addEventListener('scroll', () => {
            document.getElementById('ict-navbar').classList.toggle('scrolled', window.scrollY > 40);
        });

        /* Reveal on scroll */
        const ictRevealObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    ictRevealObserver.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1
        });
        document.querySelectorAll('.ict-reveal').forEach(el => ictRevealObserver.observe(el));

        /* Checkbox interactivity — scoped to .ict-check-opt */
        document.querySelectorAll('.ict-check-opt').forEach(opt => {
            const cb = opt.querySelector('input[type="checkbox"]');
            if (cb && cb.checked) opt.classList.add('checked');
            opt.addEventListener('click', () => {
                cb.checked = !cb.checked;
                opt.classList.toggle('checked', cb.checked);
                opt.dispatchEvent(new CustomEvent('ict-check-change', {
                    bubbles: true,
                    detail: {
                        value: cb.value,
                        checked: cb.checked
                    }
                }));
            });
        });

        /* Toast helper */
        let _ictToastTimer;

        function ictShowToast(type, title, msg, duration = 5000) {
            const toast = document.getElementById('ict-toast');
            const icon = document.getElementById('ict-toast-icon');
            const titleEl = document.getElementById('ict-toast-title');
            const msgEl = document.getElementById('ict-toast-msg');
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-exclamation-triangle-fill',
                warning: 'bi-exclamation-circle-fill'
            };
            icon.className = 'ict-toast-icon ' + type;
            icon.innerHTML = `<i class="bi ${icons[type] || icons.warning}"></i>`;
            titleEl.textContent = title;
            msgEl.textContent = msg;
            toast.classList.add('show');
            clearTimeout(_ictToastTimer);
            _ictToastTimer = setTimeout(ictHideToast, duration);
        }

        function ictHideToast() {
            document.getElementById('ict-toast').classList.remove('show');
        }

        /* Laravel flash messages */
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () =>
                ictShowToast('success', 'Success!', @json(session('success'))));
        @endif
        @if (session('error'))
            document.addEventListener('DOMContentLoaded', () =>
                ictShowToast('error', 'Error', @json(session('error'))));
        @endif
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () =>
                ictShowToast('error', 'Please check your inputs', 'Some fields need attention.'));
        @endif
    </script>
    @yield('scripts')

</body>

</html>
