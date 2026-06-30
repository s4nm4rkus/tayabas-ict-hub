<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ICT Unit — SDO Tayabas City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
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
            --radius: 16px;
            --radius-sm: 10px;
            --hero-bg: url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=2400&q=80');
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

        /* ═══ NAVBAR ═══ */
        #navbar {
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

        #navbar.scrolled {
            box-shadow: 0 4px 24px rgba(11, 31, 58, 0.10);
        }

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
            color: #f0c040;
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
            transition: color .2s;
            white-space: nowrap;
        }

        .nav-back:hover {
            color: var(--navy);
        }

        .nav-divider {
            width: 1px;
            height: 18px;
            background: rgba(26, 74, 138, 0.15);
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

        .nav-links {
            display: flex;
            gap: 2px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 7px;
            transition: all .2s;
        }

        .nav-links a:hover {
            color: var(--navy);
            background: rgba(26, 74, 138, 0.05);
        }

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

            .nav-links {
                display: none;
            }
        }

        /* ═══ HERO ═══ */
        .unit-hero {
            min-height: 92vh;
            position: relative;
            display: flex;
            align-items: center;
            padding: 110px 6% 90px;
            overflow: hidden;
        }

        .hero-photo {
            position: absolute;
            inset: 0;
            background: var(--hero-bg) center/cover no-repeat;
            z-index: 0;
        }

        .hero-photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, rgba(7, 14, 26, 0.94) 0%, rgba(7, 14, 26, 0.80) 48%, rgba(7, 14, 26, 0.70) 100%), radial-gradient(ellipse at 15% 60%, rgba(37, 99, 235, 0.16) 0%, transparent 52%);
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 1;
        }

        .hero-orb {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240, 192, 64, 0.09) 0%, transparent 65%);
            pointer-events: none;
            z-index: 1;
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

        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(240, 192, 64, 0.85);
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            transition: color .2s;
        }

        .breadcrumb a:hover {
            color: #fff;
        }

        .breadcrumb-sep {
            color: rgba(255, 255, 255, 0.2);
        }

        .hero-title {
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 800;
            color: #fff;
            line-height: 1.08;
            letter-spacing: -0.025em;
            margin-bottom: 18px;
        }

        .hero-title .accent {
            color: var(--h-gold);
        }

        .hero-desc {
            font-size: 15px;
            color: var(--h-text);
            line-height: 1.82;
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
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px var(--accent-glow);
            transition: all .25s;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.40);
        }

        .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: all .25s;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.32);
        }

        .hero-services-preview {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .hero-svc-pill {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--h-border);
            border-radius: 12px;
            padding: 16px;
            transition: all .25s;
            cursor: default;
        }

        .hero-svc-pill:hover {
            background: rgba(255, 255, 255, 0.11);
            border-color: rgba(240, 192, 64, 0.28);
            transform: translateY(-3px);
        }

        .hero-svc-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.18);
            border: 1px solid rgba(37, 99, 235, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-2);
            font-size: 15px;
            margin-bottom: 10px;
        }

        .hero-svc-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }

        .hero-svc-desc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.42);
            margin-top: 4px;
            line-height: 1.5;
        }

        .hero-stats-row {
            display: flex;
            margin-top: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--h-border);
            border-radius: 12px;
            overflow: hidden;
        }

        .hero-stat-item {
            flex: 1;
            text-align: center;
            padding: 15px 10px;
            border-right: 1px solid var(--h-border);
        }

        .hero-stat-item:last-child {
            border-right: none;
        }

        .hero-stat-num {
            font-size: 20px;
            font-weight: 800;
            color: var(--h-gold);
            line-height: 1;
        }

        .hero-stat-lbl {
            font-size: 9.5px;
            color: rgba(255, 255, 255, 0.38);
            font-weight: 500;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        @media(max-width:900px) {
            .hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero-right {
                display: none;
            }
        }

        /* ═══ TICKER ═══ */
        .ticker-wrap {
            overflow: hidden;
            border-top: 1px solid rgba(26, 74, 138, 0.10);
            border-bottom: 1px solid rgba(26, 74, 138, 0.10);
            background: var(--light);
            padding: 13px 0;
            position: relative;
        }

        .ticker-wrap::before,
        .ticker-wrap::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px;
            z-index: 2;
            pointer-events: none;
        }

        .ticker-wrap::before {
            left: 0;
            background: linear-gradient(90deg, var(--light), transparent);
        }

        .ticker-wrap::after {
            right: 0;
            background: linear-gradient(270deg, var(--light), transparent);
        }

        .ticker-track {
            display: flex;
            width: max-content;
            animation: ticker 35s linear infinite;
        }

        .ticker-track:hover {
            animation-play-state: paused;
        }

        @keyframes ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0 30px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
        }

        .ticker-item i {
            color: var(--accent);
            font-size: 7px;
        }

        /* ═══ SHARED ═══ */
        section {
            padding: 80px 6%;
        }

        .section-label {
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

        .section-label::before {
            content: '';
            display: block;
            width: 22px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(24px, 2.8vw, 36px);
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }

        .section-desc {
            font-size: 14.5px;
            color: var(--muted);
            line-height: 1.78;
            max-width: 540px;
        }

        /* ═══ SERVICES ═══ */
        .services-section {
            background: var(--light);
        }

        .services-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 44px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .svc-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 28px;
            transition: all .3s;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            color: inherit;
        }

        .svc-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), #4d8ef8);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .svc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 48px rgba(11, 31, 58, 0.11);
        }

        .svc-card:hover::before {
            transform: scaleX(1);
        }

        .svc-featured {
            background: linear-gradient(145deg, #FAFCFF, #EEF4FF);
            border: 2px solid rgba(26, 74, 138, 0.18);
        }

        .svc-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            background: rgba(37, 99, 235, 0.10);
            color: var(--accent);
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            width: fit-content;
        }

        .svc-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
            border: 1px solid;
            transition: transform .3s;
        }

        .svc-card:hover .svc-icon {
            transform: scale(1.08) rotate(-4deg);
        }

        .svc-name {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 9px;
        }

        .svc-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.68;
            margin-bottom: 16px;
            flex: 1;
        }

        .svc-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 18px;
        }

        .svc-tag {
            font-size: 10.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 4px;
            background: rgba(26, 74, 138, 0.06);
            color: var(--blue);
            border: 1px solid rgba(26, 74, 138, 0.12);
        }

        .svc-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--accent);
            margin-top: auto;
            transition: gap .2s;
        }

        .svc-card:hover .svc-cta {
            gap: 10px;
        }

        /* ═══ STEPS ═══ */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 44px;
        }

        .step-card {
            text-align: center;
            padding: 32px 20px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            transition: all .3s;
        }

        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(11, 31, 58, 0.09);
        }

        .step-num {
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
            margin: 0 auto 16px;
            box-shadow: 0 6px 16px var(--accent-glow);
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

        /* ═══ FORMS ═══ */
        .forms-section {
            background: var(--light);
        }

        .forms-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 44px;
        }

        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px 26px;
            display: flex;
            align-items: center;
            gap: 18px;
            text-decoration: none;
            color: inherit;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--accent), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .form-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(11, 31, 58, 0.10);
            border-color: rgba(26, 74, 138, 0.22);
        }

        .form-card:hover::before {
            opacity: 1;
        }

        .form-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            border: 1px solid;
            transition: transform .25s;
        }

        .form-card:hover .form-icon {
            transform: scale(1.08) rotate(-3deg);
        }

        .form-info {
            flex: 1;
        }

        .form-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
        }

        .form-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        .form-arrow {
            color: var(--muted);
            font-size: 16px;
            flex-shrink: 0;
            transition: color .2s, transform .2s;
        }

        .form-card:hover .form-arrow {
            color: var(--accent);
            transform: translateX(4px);
        }

        /* ═══ ANNOUNCEMENTS ═══ */
        .announce-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
        }

        .ann-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            display: flex;
            gap: 16px;
            transition: all .25s;
        }

        .ann-card:hover {
            box-shadow: 0 8px 28px rgba(11, 31, 58, 0.08);
            border-color: rgba(26, 74, 138, 0.20);
        }

        .ann-date-box {
            flex-shrink: 0;
            text-align: center;
            width: 48px;
        }

        .ann-day {
            font-size: 26px;
            font-weight: 800;
            color: var(--blue);
            line-height: 1;
        }

        .ann-month {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .ann-sep {
            width: 1px;
            background: var(--border);
            align-self: stretch;
            flex-shrink: 0;
        }

        .ann-body {
            flex: 1;
        }

        .ann-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--accent);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .ann-title {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .ann-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ═══ ABOUT ═══ */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
            margin-top: 44px;
        }

        .about-img-wrap {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
        }

        .about-img-wrap img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
        }

        .about-img-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(26, 74, 138, 0.18), transparent);
            border-radius: 20px;
        }

        .about-img-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            z-index: 2;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(11, 31, 58, 0.15);
            backdrop-filter: blur(10px);
        }

        .about-badge-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        .about-badge-text strong {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }

        .about-badge-text span {
            font-size: 11px;
            color: var(--muted);
        }

        .about-text p {
            font-size: 14.5px;
            color: var(--muted);
            line-height: 1.85;
            margin-bottom: 18px;
        }

        .about-text p strong {
            color: var(--navy);
        }

        .about-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .btn-outline-sm {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            color: var(--navy);
            border: 1.5px solid var(--border);
            background: transparent;
            transition: all .2s;
        }

        .btn-outline-sm:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(37, 99, 235, 0.04);
        }

        /* Purpose cards */
        .purpose-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 44px;
        }

        .purpose-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            transition: all .3s;
            position: relative;
            overflow: hidden;
        }

        .purpose-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .purpose-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(11, 31, 58, 0.10);
        }

        .purpose-card:hover::after {
            transform: scaleX(1);
        }

        .purpose-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--accent);
            margin-bottom: 18px;
            transition: transform .3s;
        }

        .purpose-card:hover .purpose-icon {
            transform: scale(1.08) rotate(-4deg);
        }

        .purpose-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .purpose-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.7;
        }

        /* Team */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 44px;
        }

        .team-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: all .3s;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(11, 31, 58, 0.12);
        }

        .team-photo {
            width: 100%;
            height: 220px;
            object-fit: cover;
            object-position: center top;
            display: block;
        }

        .team-photo-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, rgba(26, 74, 138, 0.10), rgba(37, 99, 235, 0.06));
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: rgba(26, 74, 138, 0.25);
        }

        .team-info {
            padding: 20px 22px;
        }

        .team-role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--accent);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .team-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 3px;
        }

        .team-position {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }

        /* ═══ CONTACT ═══ */
        .contact-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: start;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .contact-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .contact-icon-box {
            width: 42px;
            height: 42px;
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

        .cta-box {
            background: linear-gradient(135deg, var(--navy), #0f2d52);
            border-radius: 20px;
            padding: 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-top: 28px;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.14), transparent 65%);
            pointer-events: none;
        }

        .cta-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--accent-2);
            margin: 0 auto 18px;
        }

        .cta-title {
            font-size: 19px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .cta-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.52);
            line-height: 1.75;
            margin-bottom: 22px;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            box-shadow: 0 6px 22px var(--accent-glow);
            transition: all .25s;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(37, 99, 235, 0.42);
        }

        /* Message form */
        .msg-form-box {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 8px 32px rgba(11, 31, 58, 0.06);
        }

        .msg-form-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .msg-form-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .f-group {
            margin-bottom: 18px;
        }

        .f-group label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .f-group input,
        .f-group textarea,
        .f-group select {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: 13.5px;
            font-family: 'Poppins', sans-serif;
            color: var(--navy);
            background: #FAFBFD;
            transition: all .2s;
            outline: none;
            -webkit-appearance: none;
        }

        .f-group input:focus,
        .f-group textarea:focus,
        .f-group select:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .f-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .f-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .btn-send {
            width: 100%;
            padding: 13px 24px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 6px 22px var(--accent-glow);
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.40);
        }

        /* ═══ FOOTER ═══ */
        .unit-footer {
            background: var(--navy);
            padding: 30px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-left strong {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.70);
            margin-bottom: 2px;
        }

        .footer-left p {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.38);
        }

        .footer-back {
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

        .footer-back:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        /* ═══ REVEAL ═══ */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .d1 {
            transition-delay: .10s;
        }

        .d2 {
            transition-delay: .20s;
        }

        .d3 {
            transition-delay: .30s;
        }

        .d4 {
            transition-delay: .40s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .a1 {
            opacity: 0;
            animation: fadeUp .75s ease .10s forwards;
        }

        .a2 {
            opacity: 0;
            animation: fadeUp .75s ease .22s forwards;
        }

        .a3 {
            opacity: 0;
            animation: fadeUp .75s ease .34s forwards;
        }

        .a4 {
            opacity: 0;
            animation: fadeUp .75s ease .46s forwards;
        }

        .a5 {
            opacity: 0;
            animation: fadeUp .75s ease .58s forwards;
        }

        @media(max-width:900px) {
            .about-grid {
                grid-template-columns: 1fr;
            }

            .contact-inner {
                grid-template-columns: 1fr;
            }

            .announce-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:700px) {
            .forms-grid {
                grid-template-columns: 1fr;
            }

            .f-row {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:640px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .steps-grid {
                grid-template-columns: 1fr 1fr;
            }

            .purpose-grid {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            section {
                padding: 60px 5%;
            }
        }
    </style>
</head>

<body>

    <!-- ─── NAVBAR ─── -->
    <nav id="navbar">
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
            <span class="nav-unit-badge"><i class="bi bi-cpu-fill"></i> ICT Unit</span>
            <a href="{{ route('home') }}#units" class="nav-back"><i class="bi bi-chevron-left"></i> Back to SDO</a>
            <span class="nav-divider"></span>
            <ul class="nav-links">
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#announcements">Updates</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>



        <a href="{{ route('ict.forms') }}" class="btn-nav">
            <i class="bi bi-ticket-perforated-fill"></i> Submit a Ticket
        </a>
    </nav>


    <!-- ─── HERO ─── -->
    <div class="unit-hero">
        <div class="hero-photo"></div>
        <div class="hero-grid"></div>
        <div class="hero-orb"></div>

        <div class="hero-inner">
            <div class="hero-text">
                <div class="breadcrumb a1">
                    <a href="{{ route('home') }}">SDO Tayabas City</a>
                    <span class="breadcrumb-sep">/</span>
                    <span>ICT Unit</span>
                </div>
                <h1 class="hero-title a2">
                    Information and<br>Communication Technology<br>
                    <span class="accent">Unit</span>
                </h1>
                <p class="hero-desc a3">
                    Powering the digital infrastructure of the Schools Division Office of Tayabas City.
                    From network support and device repair to systems development and online ticketing —
                    built for every school, every teacher, every day.
                </p>
                <div class="hero-cta a4">
                    <a href="{{ route('ict.forms') }}" class="btn-hero-primary">
                        <i class="bi bi-ticket-perforated-fill"></i> Submit a Ticket
                    </a>
                    <a href="#services" class="btn-hero-outline">
                        <i class="bi bi-grid-1x2"></i> Our Services
                    </a>
                </div>
            </div>

            <div class="hero-right a5">
                <div class="hero-services-preview">
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-hdd-network-fill"></i></div>
                        <div class="hero-svc-name">Network Support</div>
                        <div class="hero-svc-desc">LAN, WAN &amp; WiFi</div>
                    </div>
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-pc-display-horizontal"></i></div>
                        <div class="hero-svc-name">Device Repair</div>
                        <div class="hero-svc-desc">Hardware &amp; diagnostics</div>
                    </div>
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <div class="hero-svc-name">Cybersecurity</div>
                        <div class="hero-svc-desc">DPA compliance</div>
                    </div>
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                        <div class="hero-svc-name">Data Management</div>
                        <div class="hero-svc-desc">Backup &amp; recovery</div>
                    </div>
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-code-slash"></i></div>
                        <div class="hero-svc-name">Systems Dev</div>
                        <div class="hero-svc-desc">Portals &amp; tools</div>
                    </div>
                    <div class="hero-svc-pill">
                        <div class="hero-svc-icon"><i class="bi bi-headset"></i></div>
                        <div class="hero-svc-name">Tech Support</div>
                        <div class="hero-svc-desc">Helpdesk &amp; guidance</div>
                    </div>
                </div>
                <div class="hero-stats-row">
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">K–12</div>
                        <div class="hero-stat-lbl">Schools</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">IV-A</div>
                        <div class="hero-stat-lbl">Region</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">6+</div>
                        <div class="hero-stat-lbl">Services</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">4</div>
                        <div class="hero-stat-lbl">ICT Forms</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ─── TICKER ─── -->
    <div class="ticker-wrap">
        <div class="ticker-track">
            @foreach (array_fill(0, 2, ['Network &amp; Connectivity Support', 'Hardware Repair &amp; Maintenance', 'Software &amp; Systems Management', 'Data Backup &amp; Recovery', 'Cybersecurity &amp; DPA Compliance', 'ICT Technical Assistance Form', 'DTS Request Form', 'Email Account Requests', 'Help Desk Form', 'Preventive Maintenance Program']) as $items)
                @foreach ($items as $item)
                    <span class="ticker-item"><i class="bi bi-circle-fill"></i> {!! $item !!}</span>
                @endforeach
            @endforeach
        </div>
    </div>


    <!-- ─── SERVICES ─── -->
    <section class="services-section" id="services">
        <div class="services-header">
            <div class="reveal">
                <div class="section-label">What We Offer</div>
                <h2 class="section-title">ICT Services</h2>
                <p class="section-desc">Technical and digital support for all schools and offices under the Schools
                    Division of Tayabas City.</p>
            </div>
            <a href="{{ route('ict.forms') }}" class="btn-hero-primary reveal d2"
                style="align-self:center;white-space:nowrap;">
                <i class="bi bi-plus-circle-fill"></i> Submit a Request
            </a>
        </div>
        <div class="services-grid">
            <a href="{{ route('ict.forms') }}" class="svc-card svc-featured reveal d1">
                <span class="svc-pill"><i class="bi bi-lightning-fill" style="font-size:9px;"></i> Online
                    Portal</span>
                <div class="svc-icon"
                    style="background:rgba(26,74,138,0.10);color:var(--blue);border-color:rgba(26,74,138,0.18);"><i
                        class="bi bi-ticket-perforated-fill"></i></div>
                <div class="svc-name">ICT Ticketing System</div>
                <div class="svc-desc">Submit, track, and manage your ICT support requests online through the official
                    SDO Tayabas Portal. Faster response times and real-time status updates.</div>
                <div class="svc-tags"><span class="svc-tag">Technical Assistance</span><span class="svc-tag">DTS
                        Request</span><span class="svc-tag">Email Request</span><span class="svc-tag">Help Desk</span>
                </div>
                <span class="svc-cta">Submit a Ticket <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('ict.forms') }}" class="svc-card reveal d2">
                <div class="svc-icon"
                    style="background:rgba(5,150,105,0.10);color:#059669;border-color:rgba(5,150,105,0.18);"><i
                        class="bi bi-hdd-network-fill"></i></div>
                <div class="svc-name">Network &amp; Connectivity</div>
                <div class="svc-desc">Installation, maintenance, and troubleshooting of LAN, WAN, and wireless network
                    infrastructure across all schools and offices.</div>
                <div class="svc-tags"><span class="svc-tag">Network Setup</span><span class="svc-tag">WiFi
                        Troubleshooting</span><span class="svc-tag">Connectivity Issues</span></div>
                <span class="svc-cta">Request Service <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('ict.forms') }}" class="svc-card reveal d3">
                <div class="svc-icon"
                    style="background:rgba(245,158,11,0.10);color:#D97706;border-color:rgba(245,158,11,0.18);"><i
                        class="bi bi-pc-display-horizontal"></i></div>
                <div class="svc-name">Hardware Support &amp; Repair</div>
                <div class="svc-desc">Diagnosis, repair, and maintenance of computers, printers, projectors, and other
                    ICT equipment used in schools and the division office.</div>
                <div class="svc-tags"><span class="svc-tag">Diagnostics</span><span
                        class="svc-tag">Repair</span><span class="svc-tag">Preventive Maintenance</span></div>
                <span class="svc-cta">Submit Repair Request <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('ict.forms') }}" class="svc-card reveal d1">
                <div class="svc-icon"
                    style="background:rgba(139,92,246,0.10);color:#8B5CF6;border-color:rgba(139,92,246,0.18);"><i
                        class="bi bi-code-slash"></i></div>
                <div class="svc-name">Software &amp; Systems</div>
                <div class="svc-desc">OS installation, software deployment, system updates, and management of DepEd
                    digital platforms, portals, and email accounts.</div>
                <div class="svc-tags"><span class="svc-tag">OS Reinstallation</span><span class="svc-tag">DepEd
                        Portals</span><span class="svc-tag">Email Accounts</span></div>
                <span class="svc-cta">Submit Request <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('ict.forms') }}" class="svc-card reveal d2">
                <div class="svc-icon"
                    style="background:rgba(37,99,235,0.10);color:var(--accent);border-color:rgba(37,99,235,0.18);"><i
                        class="bi bi-cloud-arrow-up-fill"></i></div>
                <div class="svc-name">Data Management &amp; Backup</div>
                <div class="svc-desc">Data recovery, backup solutions, and secure file management for critical division
                    records and information systems.</div>
                <div class="svc-tags"><span class="svc-tag">Data Recovery</span><span class="svc-tag">Backup
                        Setup</span><span class="svc-tag">Secure Deletion</span></div>
                <span class="svc-cta">Request Assistance <i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="#contact" class="svc-card reveal d3">
                <div class="svc-icon"
                    style="background:rgba(239,68,68,0.10);color:#EF4444;border-color:rgba(239,68,68,0.18);"><i
                        class="bi bi-shield-lock-fill"></i></div>
                <div class="svc-name">Cybersecurity &amp; Compliance</div>
                <div class="svc-desc">Protection of school systems from cyber threats, malware removal, and guidance on
                    DepEd Data Privacy Act compliance requirements.</div>
                <div class="svc-tags"><span class="svc-tag">Malware Removal</span><span class="svc-tag">DPA
                        Compliance</span><span class="svc-tag">Security Advisory</span></div>
                <span class="svc-cta">Inquire <i class="bi bi-arrow-right"></i></span>
            </a>
        </div>
    </section>





    <!-- ─── ANNOUNCEMENTS ─── -->
    <section id="announcements">
        <div class="reveal">
            <div class="section-label">Updates</div>
            <h2 class="section-title">ICT Announcements</h2>
            <p class="section-desc">Latest notices, system advisories, and updates from the ICT Unit.</p>
        </div>
        <div class="announce-grid">
            <div class="ann-card reveal d1">
                <div class="ann-date-box">
                    <div class="ann-day">05</div>
                    <div class="ann-month">May</div>
                </div>
                <div class="ann-sep"></div>
                <div class="ann-body"><span class="ann-tag">System Update</span>
                    <div class="ann-title">Scheduled Maintenance — DepEd Portals</div>
                    <div class="ann-desc">Expect intermittent access to DepEd online platforms on May 10, 8AM–12PM for
                        scheduled maintenance.</div>
                </div>
            </div>
            <div class="ann-card reveal d2">
                <div class="ann-date-box">
                    <div class="ann-day">28</div>
                    <div class="ann-month">Apr</div>
                </div>
                <div class="ann-sep"></div>
                <div class="ann-body"><span class="ann-tag">Advisory</span>
                    <div class="ann-title">Anti-Phishing Reminder for All Personnel</div>
                    <div class="ann-desc">Do not click suspicious links in emails. Report phishing attempts immediately
                        to the ICT Unit.</div>
                </div>
            </div>
            <div class="ann-card reveal d1">
                <div class="ann-date-box">
                    <div class="ann-day">22</div>
                    <div class="ann-month">Apr</div>
                </div>
                <div class="ann-sep"></div>
                <div class="ann-body"><span class="ann-tag">Notice</span>
                    <div class="ann-title">Device Inventory Submission — Deadline April 30</div>
                    <div class="ann-desc">All school heads must submit updated ICT device inventory reports through the
                        designated form.</div>
                </div>
            </div>
            <div class="ann-card reveal d2">
                <div class="ann-date-box">
                    <div class="ann-day">15</div>
                    <div class="ann-month">Apr</div>
                </div>
                <div class="ann-sep"></div>
                <div class="ann-body"><span class="ann-tag">Memorandum</span>
                    <div class="ann-title">WiFi Password Update — Division Office Network</div>
                    <div class="ann-desc">New network credentials have been issued to authorized personnel. Contact ICT
                        Unit for access.</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ─── ABOUT ─── -->
    <section id="about" style="background:var(--light);">
        <div class="reveal">
            <div class="section-label">Who We Are</div>
            <h2 class="section-title">About the ICT Unit</h2>
            <p class="section-desc">The team behind the digital infrastructure and services of SDO Tayabas City.</p>
        </div>

        <div class="about-grid">
            <div class="about-text reveal d1">
                <p>
                    The ICT Unit is responsible for handling <strong>technical support, system management, and
                        IT-related services</strong>
                    for the Schools Division Office of Tayabas City, Region IV-A CALABARZON.
                </p>
                <p>
                    The <strong>ICT Ticketing Form Website</strong> is an online platform designed to streamline
                    requests for technical
                    assistance and document-related services. This system enables faculty, staff, and other personnel to
                    conveniently
                    submit ICT-related concerns and track their requests efficiently.
                </p>
                <p>
                    By utilizing this platform, we aim to enhance service efficiency, improve response time, and provide
                    seamless
                    support for all ICT-related needs across the division.
                </p>
                <div class="about-actions">
                    <a href="{{ route('ict.forms') }}" class="btn-hero-primary"
                        style="font-size:13px;padding:11px 22px;">
                        <i class="bi bi-ticket-perforated-fill"></i> Submit a Ticket
                    </a>
                    <a href="https://www.sdotayabascity.ph" class="btn-outline-sm" target="_blank">
                        <i class="bi bi-globe2"></i> SDO Website
                    </a>
                </div>
            </div>
            <div class="about-img-wrap reveal d2">
                <img src="./Images/coversdo.jpg" alt="SDO Tayabas City"
                    onerror="this.parentElement.style.background='linear-gradient(135deg,rgba(26,74,138,0.12),rgba(37,99,235,0.06))'">
                <div class="about-img-badge">
                    <div class="about-badge-icon"><i class="bi bi-cpu-fill"></i></div>
                    <div class="about-badge-text">
                        <strong>SDO Tayabas Portal</strong>
                        <span>tayabasicthub.com</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purpose -->
        <div style="margin-top:72px;">
            <div class="reveal">
                <div class="section-label">Our Purpose</div>
                <h2 class="section-title">What This System Covers</h2>
                <p class="section-desc">A centralized hub for processing all ICT-related requests across the division.
                </p>
            </div>
            <div class="purpose-grid">
                <div class="purpose-card reveal d1">
                    <div class="purpose-icon"><i class="bi bi-tools"></i></div>
                    <div class="purpose-title">Technical Assistance</div>
                    <div class="purpose-desc">Repair, network management, software installation, configuration, and
                        internet connectivity issues for all schools and offices.</div>
                </div>
                <div class="purpose-card reveal d2">
                    <div class="purpose-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <div class="purpose-title">Document Services</div>
                    <div class="purpose-desc">Requesting retrieval, editing, or cancellation of official documents
                        through the Division's Document Tracking System.</div>
                </div>
                <div class="purpose-card reveal d3">
                    <div class="purpose-icon"><i class="bi bi-envelope-open-text"></i></div>
                    <div class="purpose-title">Email Support</div>
                    <div class="purpose-desc">Creating or resetting official DepEd email accounts for teaching and
                        non-teaching personnel across the division.</div>
                </div>
                <div class="purpose-card reveal d4">
                    <div class="purpose-icon"><i class="bi bi-headset"></i></div>
                    <div class="purpose-title">Help Desk Inquiries</div>
                    <div class="purpose-desc">Submitting general IT concerns for evaluation and resolution by the ICT
                        Unit's technical staff.</div>
                </div>
            </div>
        </div>

        <!-- Team -->
        <div style="margin-top:72px;">
            <div class="reveal">
                <div class="section-label">Our Team</div>
                <h2 class="section-title">ICT Unit Personnel</h2>
                <p class="section-desc">The dedicated people behind the digital services of SDO Tayabas City.</p>
            </div>
            <div class="team-grid">
                <div class="team-card reveal d1">
                    <img src="./Images/3.jpg" alt="Mark Bryan F. Valencia" class="team-photo"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="team-photo-placeholder"><i class="bi bi-person"></i></div>
                    <div class="team-info">
                        <span class="team-role-badge">ICT Lead</span>
                        <div class="team-name">Mark Bryan F. Valencia</div>
                        <div class="team-position">Information Technology Officer I</div>
                    </div>
                </div>
                <div class="team-card reveal d2">
                    <img src="./Images/2.jpg" alt="Celedonio B. Balderas Jr." class="team-photo"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="team-photo-placeholder"><i class="bi bi-person"></i></div>
                    <div class="team-info">
                        <span class="team-role-badge">Division Head</span>
                        <div class="team-name">Celedonio "Don" B. Balderas, Jr.</div>
                        <div class="team-position">Schools Division Superintendent</div>
                    </div>
                </div>
                <div class="team-card reveal d3">
                    <img src="./Images/1.jpg" alt="Herbert D. Perez" class="team-photo"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="team-photo-placeholder"><i class="bi bi-person"></i></div>
                    <div class="team-info">
                        <span class="team-role-badge">ASDS</span>
                        <div class="team-name">Herbert D. Perez</div>
                        <div class="team-position">Assistant Schools Division Superintendent</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ─── CONTACT + MESSAGE US ─── -->
    <section id="contact">
        <div class="reveal" style="margin-bottom:44px;">
            <div class="section-label">Get in Touch</div>
            <h2 class="section-title">Contact &amp; Message Us</h2>
            <p class="section-desc">For urgent concerns, system outages, or general inquiries — reach out or send a
                direct message below.</p>
        </div>

        <div class="contact-inner">

            <!-- Left: info + ticket CTA -->
            <div>
                <div class="reveal d1">
                    <div class="contact-info-item">
                        <div class="contact-icon-box"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contact-label">Office Location</div>
                            <div class="contact-value">ICT Unit Office, SDO Tayabas City, Quezon Province</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-icon-box"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="contact-label">Office Hours</div>
                            <div class="contact-value">Monday – Friday, 8:00 AM – 5:00 PM</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-icon-box"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contact-label">Telephone</div>
                            <div class="contact-value">(042) XXX-XXXX ext. XXX</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-icon-box"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contact-label">Email Address</div>
                            <div class="contact-value">ict.tayabas.city@deped.gov.ph</div>
                        </div>
                    </div>
                    {{-- <div class="contact-info-item">
                        <div class="contact-icon-box"><i class="bi bi-globe2"></i></div>
                        <div>
                            <div class="contact-label">ICT Hub Portal</div>
                            <div class="contact-value">tayabasicthub.com</div>
                        </div>
                    </div> --}}
                </div>

                <div class="cta-box reveal d2">
                    <div class="cta-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
                    <div class="cta-title">ICT Ticketing System</div>
                    <div class="cta-desc">Submit your technical concern online at the official SDO Tayabas Portal for a
                        faster, trackable response from the ICT Unit.</div>
                    <a href="{{ route('ict.forms') }}" class="btn-cta">
                        <i class="bi bi-plus-circle-fill"></i> Submit a Ticket
                    </a>
                    <div style="margin-top:12px;font-size:11px;color:rgba(255,255,255,0.34);">
                        Available to all DepEd Tayabas City personnel and public schools.
                    </div>
                </div>
            </div>

            <!-- Right: message form -->
            <div class="msg-form-box reveal d3">
                <div class="msg-form-title">Send Us a Message</div>
                <div class="msg-form-sub">Have a question that doesn't require a ticket? Drop us a message and we'll
                    get back to you.</div>

                <form action="send_message.php" method="post" autocomplete="off">
                    <div class="f-row">
                        <div class="f-group">
                            <label for="msg_name">Your Name</label>
                            <input type="text" id="msg_name" name="name" placeholder="Juan dela Cruz"
                                required>
                        </div>
                        <div class="f-group">
                            <label for="msg_email">Email Address</label>
                            <input type="email" id="msg_email" name="email" placeholder="juan@deped.gov.ph"
                                required>
                        </div>
                    </div>
                    <div class="f-group">
                        <label for="msg_subject">Subject</label>
                        <input type="text" id="msg_subject" name="subject"
                            placeholder="e.g. Network issue at Tayabas NHS" required>
                    </div>
                    <div class="f-group">
                        <label for="msg_concern">Concern Type</label>
                        <select id="msg_concern" name="concern">
                            <option value="">Select a category…</option>
                            <option>Network / Connectivity</option>
                            <option>Hardware / Device</option>
                            <option>Software / System</option>
                            <option>Email Account</option>
                            <option>Cybersecurity</option>
                            <option>General Inquiry</option>
                        </select>
                    </div>
                    <div class="f-group">
                        <label for="msg_message">Message</label>
                        <textarea id="msg_message" name="message" placeholder="Describe your concern in detail…" required></textarea>
                    </div>
                    <button type="submit" class="btn-send">
                        <i class="bi bi-send-fill"></i> Send Message
                    </button>
                </form>
            </div>

        </div>
    </section>


    <!-- ─── FOOTER ─── -->
    <div class="unit-footer">
        <div class="footer-left">
            <strong>ICT Unit — SDO Tayabas City</strong>
            <p>© {{ date('Y') }} Schools Division Office of Tayabas City &nbsp;·&nbsp; DepEd Philippines
                &nbsp;·&nbsp; Developed by Mark Bryan F. Valencia &amp; San Mark A. Morcoso</p>
        </div>
        <a href="{{ route('home') }}" class="footer-back"><i class="bi bi-arrow-left"></i> Back to SDO Homepage</a>
    </div>


    <script>
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 40);
        });
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1
        });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>
