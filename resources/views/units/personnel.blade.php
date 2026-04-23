<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Unit — SDO Tayabas City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: var(--navy);
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 0 6%;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(26, 74, 138, 0.07);
            transition: box-shadow 0.3s;
        }

        nav.scrolled {
            box-shadow: 0 4px 24px rgba(11, 31, 58, 0.09);
        }

        /* ── Brand (identical to welcome page) ── */
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
            border-radius: 50px;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 16px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .nav-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .nav-brand-text strong {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            padding-top: 11px;
            line-height: 0;
        }

        .nav-brand-text span {
            font-size: 10.5px;
            color: var(--muted);
            line-height: 0;
        }

        /* ── Center group: back + divider + badge ── */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 14px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .nav-back:hover {
            color: var(--navy);
        }

        .nav-back i {
            font-size: 11px;
        }

        .nav-divider {
            width: 1px;
            height: 18px;
            background: rgba(26, 74, 138, 0.15);
            display: block;
            flex-shrink: 0;
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

        /* ── Right: portal button (identical to welcome page) ── */
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

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }

            /* .nav-brand-text {
                display: none;
            } */
        }

        @media (max-width: 480px) {
            .btn-nav span {
                display: none;
            }

            .btn-nav {
                padding: 9px 14px;
            }
        }

        .btn-portal {
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
        }

        .btn-portal:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 74, 138, 0.40);
        }

        /* ── Hero ── */
        .unit-hero {
            min-height: 88vh;
            background: linear-gradient(135deg, var(--navy) 0%, #0F2D52 40%, #1A3A6B 70%, #1A4A8A 100%);
            display: flex;
            align-items: center;
            padding: 100px 8% 80px;
            position: relative;
            overflow: hidden;
        }

        .unit-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .unit-hero::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201, 168, 76, 0.12) 0%, transparent 65%);
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            width: 100%;
        }

        .hero-text {}

        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(201, 168, 76, 0.8);
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #fff;
        }

        .breadcrumb-sep {
            color: rgba(255, 255, 255, 0.2);
        }

        .hero-title {
            font-size: clamp(32px, 3.5vw, 52px);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 18px;
        }

        .hero-title span {
            color: var(--gold);
        }

        .hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.8;
            max-width: 480px;
            margin-bottom: 36px;
        }

        .hero-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 30px;
            background: linear-gradient(135deg, var(--gold), #B8962A);
            color: var(--navy);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px rgba(201, 168, 76, 0.4);
            transition: all 0.25s;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201, 168, 76, 0.5);
        }

        .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.25s;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.35);
        }

        /* Hero right — Service cards preview */
        .hero-right {}

        .hero-services-preview {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .hero-service-pill {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px;
            transition: all 0.25s;
        }

        .hero-service-pill:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(201, 168, 76, 0.3);
            transform: translateY(-3px);
        }

        .hero-service-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(201, 168, 76, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 15px;
            margin-bottom: 10px;
        }

        .hero-service-name {
            font-size: 12.5px;
            font-weight: 600;
            color: #fff;
            line-height: 1.3;
        }

        .hero-service-desc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 4px;
            line-height: 1.5;
        }

        .hero-stats-row {
            display: flex;
            gap: 0;
            margin-top: 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }

        .hero-stat-item {
            flex: 1;
            text-align: center;
            padding: 14px 12px;
            border-right: 1px solid rgba(255, 255, 255, 0.07);
        }

        .hero-stat-item:last-child {
            border-right: none;
        }

        .hero-stat-num {
            font-size: 20px;
            font-weight: 800;
            color: var(--gold);
            line-height: 1;
        }

        .hero-stat-lbl {
            font-size: 9.5px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ── Sections ── */
        section {
            padding: 80px 8%;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }

        .section-label::before {
            content: '';
            display: block;
            width: 22px;
            height: 2px;
            background: var(--gold);
        }

        .section-title {
            font-size: clamp(24px, 2.8vw, 36px);
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .section-desc {
            font-size: 14.5px;
            color: var(--muted);
            line-height: 1.75;
            max-width: 540px;
        }

        /* ── Services Grid ── */
        .services-section {
            background: var(--light);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 44px;
        }

        .service-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 28px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--gold));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 44px rgba(11, 31, 58, 0.11);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .service-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .service-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 16px;
        }

        .service-requirements {
            list-style: none;
            margin-bottom: 16px;
        }

        .service-requirements li {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin-bottom: 5px;
            line-height: 1.5;
        }

        .service-requirements li::before {
            content: '';
            display: block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
            margin-top: 5px;
        }

        .service-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--blue);
            text-decoration: none;
            transition: gap 0.2s;
        }

        .service-card:hover .service-cta {
            gap: 10px;
        }

        /* Portal highlight card */
        .service-card-portal {
            background: linear-gradient(135deg, #FAFCFF, #F0F5FF);
            border: 2px solid rgba(26, 74, 138, 0.18);
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            background: rgba(201, 168, 76, 0.15);
            color: #9A6F28;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        /* ── Process Steps ── */
        .process-section {
            padding: 80px 8%;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 44px;
            position: relative;
        }

        .step-card {
            text-align: center;
            padding: 32px 20px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.3s;
            position: relative;
        }

        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(11, 31, 58, 0.09);
        }

        .step-num {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 6px 16px rgba(26, 74, 138, 0.3);
        }

        .step-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── Announcements ── */
        .unit-announce-section {
            background: var(--light);
            padding: 80px 8%;
        }

        .announce-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
        }

        .announce-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            display: flex;
            gap: 16px;
            transition: all 0.25s;
        }

        .announce-card:hover {
            box-shadow: 0 8px 28px rgba(11, 31, 58, 0.08);
            border-color: rgba(26, 74, 138, 0.2);
        }

        .announce-date-box {
            flex-shrink: 0;
            text-align: center;
            width: 48px;
        }

        .announce-day {
            font-size: 26px;
            font-weight: 800;
            color: var(--blue);
            line-height: 1;
        }

        .announce-month {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .announce-divider {
            width: 1px;
            background: var(--border);
            align-self: stretch;
            flex-shrink: 0;
        }

        .announce-content {
            flex: 1;
        }

        .announce-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            background: rgba(201, 168, 76, 0.12);
            color: #9A6F28;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .announce-title {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .announce-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── Contact ── */
        .contact-section {
            padding: 80px 8%;
        }

        .contact-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: start;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(26, 74, 138, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue);
            font-size: 16px;
            flex-shrink: 0;
        }

        .contact-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 3px;
        }

        .contact-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--navy);
        }

        .contact-portal-box {
            background: linear-gradient(135deg, var(--navy), #1A3A6B);
            border-radius: 20px;
            padding: 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .contact-portal-box::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(201, 168, 76, 0.1);
        }

        .portal-box-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(201, 168, 76, 0.15);
            border: 1px solid rgba(201, 168, 76, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: var(--gold);
            margin: 0 auto 20px;
        }

        .portal-box-title {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .portal-box-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .btn-portal-big {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--gold), #B8962A);
            color: var(--navy);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px rgba(201, 168, 76, 0.35);
            transition: all 0.25s;
        }

        .btn-portal-big:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(201, 168, 76, 0.5);
        }

        /* ── Footer ── */
        .unit-footer {
            background: var(--navy);
            padding: 32px 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .unit-footer-left {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.4);
        }

        .unit-footer-left strong {
            color: rgba(255, 255, 255, 0.7);
            display: block;
            font-size: 13.5px;
            margin-bottom: 2px;
        }

        .footer-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }

        .footer-back-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        /* Animations */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        .reveal-delay-4 {
            transition-delay: 0.4s;
        }

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

        .anim-1 {
            opacity: 0;
            animation: fadeUp 0.7s ease 0.1s forwards;
        }

        .anim-2 {
            opacity: 0;
            animation: fadeUp 0.7s ease 0.2s forwards;
        }

        .anim-3 {
            opacity: 0;
            animation: fadeUp 0.7s ease 0.3s forwards;
        }

        .anim-4 {
            opacity: 0;
            animation: fadeUp 0.7s ease 0.4s forwards;
        }

        .anim-5 {
            opacity: 0;
            animation: fadeUp 0.7s ease 0.5s forwards;
        }

        @media (max-width:900px) {
            .hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero-right {
                display: none;
            }

            .contact-inner {
                grid-template-columns: 1fr;
            }

            .announce-grid {
                grid-template-columns: 1fr;
            }

            nav .nav-unit-badge {
                display: none;
            }
        }

        @media (max-width:600px) {
            nav {
                padding: 0 4%;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .steps-grid {
                grid-template-columns: 1fr 1fr;
            }

            section {
                padding: 60px 5%;
            }
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav id="navbar">
        {{-- Brand — same structure as welcome page --}}
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

        {{-- Center: back link + unit badge --}}
        <div class="nav-center">
            <a href="{{ route('home') }}#units" class="nav-back">
                <i class="bi bi-chevron-left"></i>
                Back to SDO
            </a>
            <span class="nav-divider"></span>
            <span class="nav-unit-badge">
                <i class="bi bi-people-fill"></i> Personnel Unit
            </span>
        </div>

        {{-- Right: portal button --}}
        <a href="{{ route('login') }}" class="btn-nav">
            <i class="bi bi-box-arrow-in-right"></i> Employee Portal
        </a>
    </nav>
    {{-- Hero --}}
    <div class="unit-hero">
        <div class="hero-inner">

            <div class="hero-text">
                <div class="breadcrumb anim-1">
                    <a href="{{ route('home') }}">SDO Tayabas City</a>
                    <span class="breadcrumb-sep">/</span>
                    <span>Personnel Unit</span>
                </div>
                <h1 class="hero-title anim-2">
                    Personnel<br>
                    <span>Unit</span>
                </h1>
                <p class="hero-desc anim-3">
                    We manage the human resources of the Schools Division Office of Tayabas City —
                    from appointments and leaves to personnel development and employee welfare.
                    The Employee Portal is accessible here for authorized personnel.
                </p>
                <div class="hero-cta anim-4">
                    <a href="{{ route('login') }}" class="btn-hero-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Access Employee Portal
                    </a>
                    <a href="#services" class="btn-hero-outline">
                        <i class="bi bi-grid-1x2"></i> Our Services
                    </a>
                </div>
            </div>

            <div class="hero-right anim-5">
                <div class="hero-services-preview">
                    @foreach ([['bi-person-check', 'Appointments', 'Original, renewal & transfer'], ['bi-calendar-check', 'Leave Management', 'Applications & monitoring'], ['bi-file-earmark-text', 'Certificates', 'COE, CSR, CLB & more'], ['bi-graph-up', 'Performance', 'IPCRF & evaluation'], ['bi-award', 'Benefits', 'GSIS, PhilHealth, Pag-IBIG'], ['bi-people', 'Plantilla', 'Position management']] as [$icon, $name, $desc])
                        <div class="hero-service-pill">
                            <div class="hero-service-icon"><i class="bi {{ $icon }}"></i></div>
                            <div class="hero-service-name">{{ $name }}</div>
                            <div class="hero-service-desc">{{ $desc }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="hero-stats-row">
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">24/7</div>
                        <div class="hero-stat-lbl">Portal Access</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">6+</div>
                        <div class="hero-stat-lbl">Core Services</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">DepEd</div>
                        <div class="hero-stat-lbl">Compliant</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Services --}}
    <section class="services-section" id="services">

        <div class="reveal">
            <div class="section-label">What We Offer</div>
            <h2 class="section-title">Personnel Services</h2>
            <p class="section-desc">
                The Personnel Unit provides a wide range of human resource services to all
                employees of the Schools Division Office of Tayabas City.
            </p>
        </div>

        <div class="services-grid">

            {{-- Employee Portal --}}
            <div class="service-card service-card-portal reveal reveal-delay-1">
                <span class="portal-badge"><i class="bi bi-star-fill" style="font-size:9px;"></i> Digital Service</span>
                <div class="service-icon" style="background:rgba(26,74,138,0.1);color:#1A4A8A;">
                    <i class="bi bi-laptop"></i>
                </div>
                <div class="service-name">Employee Self-Service Portal</div>
                <div class="service-desc">
                    Access your personal records, apply for leave, request certificates, view attendance,
                    and receive official communications — all in one secure platform.
                </div>
                <ul class="service-requirements">
                    <li>Official DepEd email required</li>
                    <li>Secure OTP two-factor authentication</li>
                    <li>Available 24/7 from any device</li>
                </ul>
                <a href="{{ route('login') }}" class="service-cta">
                    Access Portal <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- Appointments --}}
            <div class="service-card reveal reveal-delay-2">
                <div class="service-icon" style="background:rgba(5,150,105,0.1);color:#059669;">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div class="service-name">Appointments & Promotions</div>
                <div class="service-desc">Processing of original appointments, renewal, promotion, transfer, and other
                    personnel actions.</div>
                <ul class="service-requirements">
                    <li>Personal Data Sheet (CS Form 212)</li>
                    <li>Certificate of Eligibility</li>
                    <li>Medical Certificate</li>
                    <li>NBI/Police Clearance</li>
                </ul>
                <a href="#contact" class="service-cta">Inquire <i class="bi bi-arrow-right"></i></a>
            </div>

            {{-- Leave --}}
            <div class="service-card reveal reveal-delay-3">
                <div class="service-icon" style="background:rgba(245,158,11,0.1);color:#D97706;">
                    <i class="bi bi-calendar2-check"></i>
                </div>
                <div class="service-name">Leave Administration</div>
                <div class="service-desc">Processing of leave applications, leave credits monitoring, and leave without
                    pay documentation.</div>
                <ul class="service-requirements">
                    <li>Accomplished Leave Application Form</li>
                    <li>Supporting documents (if applicable)</li>
                    <li>Supervisor endorsement</li>
                </ul>
                <a href="{{ route('login') }}" class="service-cta">Apply via Portal <i
                        class="bi bi-arrow-right"></i></a>
            </div>

            {{-- Certificates --}}
            <div class="service-card reveal reveal-delay-1">
                <div class="service-icon" style="background:rgba(139,92,246,0.1);color:#8B5CF6;">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div class="service-name">Certificate Requests</div>
                <div class="service-desc">Issuance of Certificate of Employment, Service Record, Certificate of Leave
                    Balance, and other official certifications.</div>
                <ul class="service-requirements">
                    <li>Request letter or portal submission</li>
                    <li>Valid employee ID</li>
                    <li>Purpose of the certificate</li>
                </ul>
                <a href="{{ route('login') }}" class="service-cta">Request via Portal <i
                        class="bi bi-arrow-right"></i></a>
            </div>

            {{-- Performance --}}
            <div class="service-card reveal reveal-delay-2">
                <div class="service-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="service-name">Performance Evaluation</div>
                <div class="service-desc">Administration of IPCRF, RPMS, and other performance rating systems for
                    teaching and non-teaching personnel.</div>
                <ul class="service-requirements">
                    <li>Accomplished IPCRF Form</li>
                    <li>Means of Verification (MOV)</li>
                    <li>School head endorsement</li>
                </ul>
                <a href="#contact" class="service-cta">Inquire <i class="bi bi-arrow-right"></i></a>
            </div>

            {{-- Benefits --}}
            <div class="service-card reveal reveal-delay-3">
                <div class="service-icon" style="background:rgba(201,168,76,0.12);color:#C9A84C;">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div class="service-name">Benefits & Welfare</div>
                <div class="service-desc">Assistance with GSIS, PhilHealth, Pag-IBIG enrollment, loan processing, and
                    employee welfare programs.</div>
                <ul class="service-requirements">
                    <li>GSIS / PhilHealth / Pag-IBIG numbers</li>
                    <li>Employment records</li>
                    <li>Completed benefit forms</li>
                </ul>
                <a href="#contact" class="service-cta">Inquire <i class="bi bi-arrow-right"></i></a>
            </div>

        </div>
    </section>

    {{-- Process Steps --}}
    <section class="process-section" id="process">
        <div class="reveal">
            <div class="section-label">How It Works</div>
            <h2 class="section-title">Using the Employee Portal</h2>
            <p class="section-desc">Getting started with the SDO Employee Portal is simple and straightforward.</p>
        </div>
        <div class="steps-grid">
            @foreach ([['1', 'Receive Credentials', 'Your official DepEd email and system-generated password will be provided by the Personnel Unit upon onboarding.'], ['2', 'Login & Verify', 'Access the Employee Portal and complete OTP verification via your official email.'], ['3', 'Change Password', 'On first login, you will be prompted to set a new secure password.'], ['4', 'Access Services', 'Browse and use available services — leave applications, certificate requests, attendance, and more.']] as [$num, $title, $desc])
                <div class="step-card reveal reveal-delay-{{ $num }}">
                    <div class="step-num">{{ $num }}</div>
                    <div class="step-title">{{ $title }}</div>
                    <div class="step-desc">{{ $desc }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Announcements --}}
    <section class="unit-announce-section" id="announcements">
        <div class="reveal">
            <div class="section-label">Updates</div>
            <h2 class="section-title">Personnel Announcements</h2>
            <p class="section-desc">Latest notices, memoranda, and advisories from the Personnel Unit.</p>
        </div>
        <div class="announce-grid">
            @foreach ([['22', 'Apr', 'Advisory', 'Deadline for Submission of Service Records', 'All employees are reminded to submit updated service records on or before April 30.', '1'], ['18', 'Apr', 'Notice', 'Updated Leave Credit Policy for SY 2025-2026', 'Refer to the attached DepEd Order for updated guidelines on leave credit computation.', '2'], ['10', 'Apr', 'Memorandum', 'IPCRF Submission Schedule — Teaching Personnel', 'Kindly submit accomplished IPCRF forms through your school heads before the deadline.', '1'], ['05', 'Apr', 'Advisory', 'GSIS Premium Adjustments Effective May 2026', 'Payroll deductions will reflect updated GSIS premiums starting next month.', '2']] as [$day, $month, $tag, $title, $desc, $delay])
                <div class="announce-card reveal reveal-delay-{{ $delay }}">
                    <div class="announce-date-box">
                        <div class="announce-day">{{ $day }}</div>
                        <div class="announce-month">{{ $month }}</div>
                    </div>
                    <div class="announce-divider"></div>
                    <div class="announce-content">
                        <span class="announce-tag">{{ $tag }}</span>
                        <div class="announce-title">{{ $title }}</div>
                        <div class="announce-desc">{{ $desc }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Contact + Portal CTA --}}
    <section class="contact-section" id="contact">
        <div class="contact-inner">
            <div>
                <div class="section-label reveal">Get in Touch</div>
                <h2 class="section-title reveal reveal-delay-1">Contact Personnel Unit</h2>
                <p class="section-desc reveal reveal-delay-2" style="margin-bottom:32px;">
                    For inquiries, document requests, and assistance not available through the portal,
                    visit us in person or reach us through these channels.
                </p>

                <div class="reveal reveal-delay-2">
                    @foreach ([['bi-geo-alt', 'Location', 'Personnel Unit Office, SDO Tayabas City, Quezon Province'], ['bi-clock', 'Office Hours', 'Monday – Friday, 8:00 AM – 5:00 PM'], ['bi-telephone', 'Telephone', '(042) XXX-XXXX ext. XXX'], ['bi-envelope', 'Email', 'personnel.tayabas.city@deped.gov.ph']] as [$icon, $label, $value])
                        <div class="contact-info-item">
                            <div class="contact-icon"><i class="bi {{ $icon }}"></i></div>
                            <div>
                                <div class="contact-label">{{ $label }}</div>
                                <div class="contact-value">{{ $value }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="reveal reveal-delay-3">
                <div class="contact-portal-box">
                    <div class="portal-box-icon"><i class="bi bi-laptop"></i></div>
                    <div class="portal-box-title">Employee Self-Service Portal</div>
                    <div class="portal-box-desc">
                        Access your HR records, apply for leave, request certificates, and more —
                        anytime, anywhere through our secure employee portal.
                    </div>
                    <a href="{{ route('login') }}" class="btn-portal-big">
                        <i class="bi bi-box-arrow-in-right"></i> Login to Portal
                    </a>
                    <div style="margin-top:14px;font-size:11px;color:rgba(255,255,255,0.35);">
                        Authorized DepEd personnel only · Official email required
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <div class="unit-footer">
        <div class="unit-footer-left">
            <strong>Personnel Unit — SDO Tayabas City</strong>
            © {{ date('Y') }} Schools Division Office of Tayabas City · DepEd Philippines
        </div>
        <a href="{{ route('home') }}" class="footer-back-btn">
            <i class="bi bi-arrow-left"></i> Back to SDO Homepage
        </a>
    </div>

    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40);
        });
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>
