<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDO Tayabas City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
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

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
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
            padding-top: 15px;
            line-height: 0;
        }

        .nav-brand-text span {
            font-size: 10.5px;
            color: var(--muted);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--navy);
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
            white-space: nowrap;
        }

        .btn-nav:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 74, 138, 0.40);
        }

        /* ════════════════════════════════════
           HERO
        ════════════════════════════════════ */
        .hero {
            position: relative;
            min-height: 90vh;
            overflow: hidden;
            background: #fff;
        }

        .hero-video {
            position: absolute;
            top: 50px;
            right: -10%;
            height: 90%;
            width: auto;
            min-width: 60%;
            object-fit: cover;
            object-position: center center;
            z-index: 0;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to right,
                    #ffffff 0%,
                    #ffffff 32%,
                    rgba(255, 255, 255, 0.96) 42%,
                    rgba(255, 255, 255, 0.75) 52%,
                    rgba(255, 255, 255, 0) 64%,
                    rgba(255, 255, 255, 0) 78%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 45px 4% 0px 12% !important;
            max-width: 900px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 22px;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.05s forwards;
        }

        .hero-eyebrow::before {
            content: '';
            width: 28px;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
            display: block;
        }

        .hero-title {
            font-size: clamp(72px, 3.8vw, 56px);
            font-weight: 800;
            line-height: 1.08;
            color: var(--navy);
            letter-spacing: -0.025em;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.15s forwards;
        }

        .hero-title .line-sm {
            display: block;
            font-size: clamp(18px, 2.1vw, 30px);
            font-weight: 500;
            color: var(--muted);
            letter-spacing: -0.01em;
        }

        .hero-title .highlight {
            color: var(--blue);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), rgba(201, 168, 76, 0));
            border-radius: 3px;
        }

        .hero-subtitle {
            font-size: clamp(13px, 1.2vw, 14.5px);
            color: var(--muted);
            line-height: 1.85;
            max-width: 420px;
            margin-bottom: 36px;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.30s forwards;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 44px;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.38s forwards;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: linear-gradient(135deg, var(--blue), var(--navy));
            color: #fff;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.25s;
            box-shadow: 0 6px 22px rgba(26, 74, 138, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 74, 138, 0.48);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: transparent;
            color: var(--navy);
            border: 1.5px solid rgba(26, 74, 138, 0.22);
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.25s;
        }

        .btn-outline:hover {
            border-color: var(--blue);
            background: rgba(26, 74, 138, 0.04);
        }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeUp 0.7s ease 0.46s forwards;
        }

        .stat {
            padding: 14px 26px 14px 0;
            position: relative;
        }

        .stat:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 13px;
            top: 22%;
            bottom: 22%;
            width: 1px;
            background: rgba(26, 74, 138, 0.15);
        }

        .stat-num {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 10px;
            color: var(--muted);
            font-weight: 500;
            margin-top: 2px;
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

        /* ════════════════════════════════════
           SHARED SECTIONS
        ════════════════════════════════════ */
        section {
            padding: 96px 8%;
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
            margin-bottom: 14px;
        }

        .section-label::before {
            content: '';
            display: block;
            width: 22px;
            height: 2px;
            background: var(--gold);
        }

        .section-title {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .section-desc {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.75;
            max-width: 560px;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s ease, transform 0.65s ease;
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

        /* ════════════════════════════════════
           MISSION STRIP
        ════════════════════════════════════ */
        .mission-strip {
            background: linear-gradient(135deg, var(--navy) 0%, #1A3560 100%);
            padding: 72px 8%;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 48px;
        }

        .mission-item {
            text-align: center;
            padding: 32px 24px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            transition: all 0.3s;
            cursor: default;
        }

        .mission-item:hover {
            border-color: rgba(201, 168, 76, 0.3);
            background: rgba(201, 168, 76, 0.06);
            transform: translateY(-4px);
        }

        .mission-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(201, 168, 76, 0.15);
            border: 1px solid rgba(201, 168, 76, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 22px;
            color: var(--gold);
            transition: all 0.3s;
        }

        .mission-item:hover .mission-icon {
            background: rgba(201, 168, 76, 0.25);
        }

        .mission-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .mission-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.7;
        }

        /* ════════════════════════════════════
           UNITS
        ════════════════════════════════════ */
        .units-section {
            background: var(--light);
        }

        .units-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 48px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .unit-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 28px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
            position: relative;
            overflow: hidden;
        }

        .unit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--gold));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .unit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(11, 31, 58, 0.12);
            border-color: rgba(26, 74, 138, 0.2);
        }

        .unit-card:hover::before {
            transform: scaleX(1);
        }

        .unit-icon {
            width: 50px;
            height: 50px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 18px;
        }

        .unit-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .unit-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65;
        }

        .unit-tag {
            display: inline-block;
            margin-top: 14px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 99px;
            letter-spacing: 0.05em;
        }

        /* ════════════════════════════════════
           ANNOUNCEMENTS
        ════════════════════════════════════ */
        .announcements-section {
            padding: 96px 8%;
        }

        .announce-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 40px;
        }

        .announce-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            display: flex;
            gap: 18px;
            transition: all 0.25s;
        }

        .announce-card:hover {
            box-shadow: 0 8px 28px rgba(11, 31, 58, 0.08);
            border-color: rgba(26, 74, 138, 0.2);
        }

        .announce-date-box {
            flex-shrink: 0;
            text-align: center;
            width: 52px;
        }

        .announce-day {
            font-size: 28px;
            font-weight: 800;
            color: var(--blue);
            line-height: 1;
        }

        .announce-month {
            font-size: 11px;
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
            min-width: 0;
        }

        .announce-tag {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            background: rgba(201, 168, 76, 0.12);
            color: #9A6F28;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .announce-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .announce-desc {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ════════════════════════════════════
           FOOTER
        ════════════════════════════════════ */
        footer {
            background: var(--navy);
            padding: 60px 8% 32px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }

        .footer-brand h3 {
            font-size: 18px;
            color: #fff;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer-brand p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
            line-height: 1.7;
        }

        .footer-col h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold);
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 9px;
        }

        .footer-col ul li a {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: #fff;
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 24px;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            flex-wrap: wrap;
            gap: 8px;
        }

        .footer-social-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .footer-social-btn:hover {
            background: rgba(201, 168, 76, 0.2);
            color: var(--gold);
        }

        /* ════════════════════════════════════
           RESPONSIVE — TABLET
        ════════════════════════════════════ */
        @media (max-width: 900px) {
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
                        #ffffff 35%,
                        rgba(255, 255, 255, 0.97) 48%,
                        rgba(255, 255, 255, 0.80) 62%,
                        rgba(255, 255, 255, 0.20) 78%,
                        rgba(255, 255, 255, 0.00) 100%);
            }

            .hero-content {
                max-width: 100%;
                padding: 110px 6% 64px;
                min-height: 100svh;
                justify-content: flex-end;
                padding-bottom: 56px;
            }

            .hero-subtitle {
                max-width: 100%;
            }

            .nav-links {
                display: none;
            }

            .mission-strip {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .announce-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        /* ════════════════════════════════════
           RESPONSIVE — MOBILE
        ════════════════════════════════════ */
        @media (max-width: 600px) {
            nav {
                padding: 0 4%;
            }

            .nav-brand-text span {
                display: none;
            }

            .btn-nav {
                padding: 8px 14px;
                font-size: 12px;
            }

            .hero-content {
                padding: 90px 5% 48px;
            }

            .hero-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-primary,
            .btn-outline {
                width: 100%;
                justify-content: center;
            }

            .stat {
                padding: 10px 20px 10px 0;
            }

            section {
                padding: 64px 5%;
            }

            .units-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .announce-card {
                flex-direction: column;
                gap: 12px;
            }

            .announce-divider {
                width: 100%;
                height: 1px;
                align-self: auto;
            }

            .announce-date-box {
                display: flex;
                align-items: baseline;
                gap: 6px;
                width: auto;
            }
        }
    </style>
</head>

<body>

    <nav id="navbar">
        <a href="#" class="nav-brand">
            <div class="nav-brand-icon">
                <img src="{{ asset('storage/logo-nav.png') }}" alt="SDO Logo" id="nav-logo"
                    onload="this.style.display='block'" onerror="this.style.display='none'">
            </div>
            <div class="nav-brand-text">
                <strong>SDO Tayabas City</strong>
                <span>Schools Division Office</span>
            </div>
        </a>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#units">Units</a></li>
            <li><a href="#announcements">Announcements</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <a href="{{ route('login') }}" class="btn-nav">
            <i class="bi bi-box-arrow-in-right"></i> Employee Portal
        </a>
    </nav>

    <section class="hero" id="home">

        <div class="hero-content">

            <div class="hero-eyebrow">Republic of the Philippines</div>

            <h1 class="hero-title">
                <span class="line-sm">Schools Division Office of</span>
                <span class="highlight">Tayabas City</span>
            </h1>

            <p class="hero-subtitle">
                Empowering educators, nurturing learners, and building a
                future-ready community through quality, accessible, and
                inclusive education in Tayabas City, Quezon Province.
            </p>

            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn-primary">
                    <i class="bi bi-person-badge"></i> Employee Portal
                </a>
                <a href="#units" class="btn-outline">
                    <i class="bi bi-grid-1x2"></i> Our Units
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">DepEd</div>
                    <div class="stat-label">Department of Education</div>
                </div>
                <div class="stat">
                    <div class="stat-num">IV-A</div>
                    <div class="stat-label">CALABARZON Region</div>
                </div>
                <div class="stat">
                    <div class="stat-num">SDO</div>
                    <div class="stat-label">Division Level Office</div>
                </div>
            </div>

        </div>

        <video class="hero-video" src="{{ asset('storage/logo-3d.mp4') }}" autoplay loop muted playsinline
            poster="#">
        </video>

        <div class="hero-overlay"></div>

    </section>

    <!-- ══ MISSION / VISION / CORE VALUES ══ -->
    <div class="mission-strip" id="about">
        <div class="mission-item reveal">
            <div class="mission-icon"><i class="bi bi-eye"></i></div>
            <div class="mission-title">Our Vision</div>
            <div class="mission-desc">
                We dream of Filipinos who passionately love their country and whose values and competencies enable them
                to realize their full potential and contribute meaningfully to building the nation.
            </div>
        </div>
        <div class="mission-item reveal reveal-delay-2">
            <div class="mission-icon"><i class="bi bi-bullseye"></i></div>
            <div class="mission-title">Our Mission</div>
            <div class="mission-desc">
                To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete
                basic education where students learn in a child-friendly, gender-sensitive, safe, and motivating
                environment.
            </div>
        </div>
        <div class="mission-item reveal reveal-delay-3">
            <div class="mission-icon"><i class="bi bi-star"></i></div>
            <div class="mission-title">Core Values</div>
            <div class="mission-desc">
                Maka-Diyos, Maka-tao, Makakalikasan, at Makabansa — guided by integrity, excellence, and a deep
                commitment to Filipino identity and values.
            </div>
        </div>
    </div>

    <!-- ══ UNITS ══ -->
    <section class="units-section" id="units">
        <div class="units-header">
            <div>
                <div class="section-label">Our Units</div>
                <h2 class="section-title">Division Office Units</h2>
                <p class="section-desc">
                    The Schools Division Office of Tayabas City is composed of dedicated units
                    working together to deliver quality education services.
                </p>
            </div>
        </div>

        <div class="units-grid">

            <div class="unit-card reveal reveal-delay-1">
                <div class="unit-icon" style="background:rgba(26,74,138,0.1);color:#1A4A8A;">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="unit-name">Office of the Schools Division Superintendent</div>
                <div class="unit-desc">Provides overall leadership, direction, and management of all programs, projects,
                    and activities of the division.</div>
                <span class="unit-tag" style="background:rgba(26,74,138,0.08);color:#1A4A8A;">Leadership</span>
            </div>

            <div class="unit-card reveal reveal-delay-2">
                <div class="unit-icon" style="background:rgba(5,150,105,0.1);color:#059669;">
                    <i class="bi bi-book"></i>
                </div>
                <div class="unit-name">Curriculum Implementation Division</div>
                <div class="unit-desc">Oversees the implementation of the K-12 curriculum, instructional supervision,
                    and learning delivery modalities.</div>
                <span class="unit-tag" style="background:rgba(5,150,105,0.08);color:#059669;">Curriculum</span>
            </div>

            <div class="unit-card reveal reveal-delay-3">
                <div class="unit-icon" style="background:rgba(139,92,246,0.1);color:#8B5CF6;">
                    <i class="bi bi-building"></i>
                </div>
                <div class="unit-name">School Governance &amp; Operations Division</div>
                <div class="unit-desc">Manages school governance, facilities, human resources, legal matters, and
                    operational concerns across the division.</div>
                <span class="unit-tag" style="background:rgba(139,92,246,0.08);color:#8B5CF6;">Governance</span>
            </div>

            <div class="unit-card reveal reveal-delay-4">
                <div class="unit-icon" style="background:rgba(201,168,76,0.12);color:#C9A84C;">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="unit-name">Administrative &amp; Finance Division</div>
                <div class="unit-desc">Handles budget management, financial reporting, procurement, payroll, and
                    general administrative services.</div>
                <span class="unit-tag" style="background:rgba(201,168,76,0.1);color:#9A6F28;">Finance</span>
            </div>

            <div class="unit-card reveal reveal-delay-1">
                <div class="unit-icon" style="background:rgba(239,68,68,0.1);color:#EF4444;">
                    <i class="bi bi-people"></i>
                </div>
                <div class="unit-name">Human Resource Management Office</div>
                <div class="unit-desc">Manages recruitment, appointments, welfare, development, and performance of all
                    division personnel.</div>
                <span class="unit-tag" style="background:rgba(239,68,68,0.08);color:#B91C1C;">HR Management</span>
            </div>

            <div class="unit-card reveal reveal-delay-2">
                <div class="unit-icon" style="background:rgba(11,31,58,0.08);color:#0B1F3A;">
                    <i class="bi bi-cpu"></i>
                </div>
                <div class="unit-name">Information &amp; Communication Technology</div>
                <div class="unit-desc">Provides ICT support, digital infrastructure, and technology integration across
                    all schools in the division.</div>
                <span class="unit-tag" style="background:rgba(11,31,58,0.06);color:#0B1F3A;">Technology</span>
            </div>

            <div class="unit-card reveal reveal-delay-3">
                <div class="unit-icon" style="background:rgba(245,158,11,0.1);color:#F59E0B;">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="unit-name">Legal &amp; Legislative Affairs</div>
                <div class="unit-desc">Provides legal counsel, handles administrative cases, and ensures compliance
                    with education laws and policies.</div>
                <span class="unit-tag" style="background:rgba(245,158,11,0.08);color:#B45309;">Legal</span>
            </div>

            <div class="unit-card reveal reveal-delay-4">
                <div class="unit-icon" style="background:rgba(236,72,153,0.1);color:#EC4899;">
                    <i class="bi bi-heart"></i>
                </div>
                <div class="unit-name">Inclusive Education Unit</div>
                <div class="unit-desc">Champions programs for learners with disabilities, indigenous peoples education,
                    and alternative learning systems.</div>
                <span class="unit-tag" style="background:rgba(236,72,153,0.08);color:#BE185D;">Inclusion</span>
            </div>

        </div>
    </section>

    <!-- ══ ANNOUNCEMENTS ══ -->
    <section class="announcements-section" id="announcements">
        <div class="section-label reveal">Announcements</div>
        <h2 class="section-title reveal reveal-delay-1">Latest Updates</h2>
        <p class="section-desc reveal reveal-delay-2">
            Stay informed with the latest memoranda, advisories, and announcements
            from the Schools Division Office.
        </p>

        <div class="announce-grid">

            <div class="announce-card reveal reveal-delay-1">
                <div class="announce-date-box">
                    <div class="announce-day">22</div>
                    <div class="announce-month">Apr</div>
                </div>
                <div class="announce-divider"></div>
                <div class="announce-content">
                    <span class="announce-tag">Advisory</span>
                    <div class="announce-title">School Year 2025-2026 Opening Ceremonies</div>
                    <div class="announce-desc">All public schools are directed to conduct opening ceremonies following
                        the prescribed DepEd guidelines.</div>
                </div>
            </div>

            <div class="announce-card reveal reveal-delay-2">
                <div class="announce-date-box">
                    <div class="announce-day">20</div>
                    <div class="announce-month">Apr</div>
                </div>
                <div class="announce-divider"></div>
                <div class="announce-content">
                    <span class="announce-tag">Memorandum</span>
                    <div class="announce-title">Division Cluster Training on K-12 Assessment</div>
                    <div class="announce-desc">All school heads and subject teachers are required to attend the
                        scheduled cluster training sessions.</div>
                </div>
            </div>

            <div class="announce-card reveal reveal-delay-1">
                <div class="announce-date-box">
                    <div class="announce-day">18</div>
                    <div class="announce-month">Apr</div>
                </div>
                <div class="announce-divider"></div>
                <div class="announce-content">
                    <span class="announce-tag">Notice</span>
                    <div class="announce-title">Submission of Quarterly Reports Due April 30</div>
                    <div class="announce-desc">School heads are reminded to submit complete Q3 reports through the
                        designated online portal.</div>
                </div>
            </div>

            <div class="announce-card reveal reveal-delay-2">
                <div class="announce-date-box">
                    <div class="announce-day">15</div>
                    <div class="announce-month">Apr</div>
                </div>
                <div class="announce-divider"></div>
                <div class="announce-content">
                    <span class="announce-tag">Advisory</span>
                    <div class="announce-title">Mental Health Awareness Program for Teachers</div>
                    <div class="announce-desc">The HRMO announces a series of webinars on teacher wellness and mental
                        health support.</div>
                </div>
            </div>

        </div>
    </section>

    <!-- ══ FOOTER ══ -->
    <footer id="contact">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>SDO Tayabas City</h3>
                <p>
                    Schools Division Office of Tayabas City<br>
                    Department of Education · Region IV-A CALABARZON<br>
                    Tayabas City, Quezon Province, Philippines
                </p>
                <div style="margin-top:16px;display:flex;gap:10px;">
                    <a href="#" class="footer-social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social-btn"><i class="bi bi-envelope"></i></a>
                    <a href="#" class="footer-social-btn"><i class="bi bi-telephone"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About SDO</a></li>
                    <li><a href="#units">Our Units</a></li>
                    <li><a href="#announcements">Announcements</a></li>
                    <li><a href="{{ route('login') }}">Employee Portal</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>DepEd Links</h4>
                <ul>
                    <li><a href="https://www.deped.gov.ph" target="_blank">DepEd Central</a></li>
                    <li><a href="#" target="_blank">DepEd Region IV-A</a></li>
                    <li><a href="#" target="_blank">DepEd Commons</a></li>
                    <li><a href="#" target="_blank">LIS Portal</a></li>
                    <li><a href="#" target="_blank">HRIS</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="#">📍 Tayabas City, Quezon</a></li>
                    <li><a href="tel:+63000000000">📞 (042) XXX-XXXX</a></li>
                    <li><a href="mailto:tayabas.city@deped.gov.ph">✉ tayabas.city@deped.gov.ph</a></li>
                    <li><a href="#">🕐 Mon–Fri, 8AM–5PM</a></li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <span>© {{ date('Y') }} Schools Division Office — Tayabas City. All rights reserved.</span>
            <span>Powered by Tayabas ICT Hub · DepEd Philippines</span>
        </div>
    </footer>

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
            threshold: 0.12
        });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>
