<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Tayabas ICT Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-start: #6EA8FE;
            --primary-end: #4A90E2;
            --accent: #8B5CF6;
            --accent-light: #A78BFA;
            --bg: #F0F4FF;
            --surface: #FFFFFF;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --border: rgba(110, 168, 254, 0.15);
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-width: 260px;
            --shadow-sm: 0 1px 3px rgba(74, 144, 226, 0.08), 0 1px 2px rgba(74, 144, 226, 0.06);
            --shadow-md: 0 4px 16px rgba(74, 144, 226, 0.12), 0 2px 6px rgba(74, 144, 226, 0.08);
            --radius: 14px;
            --radius-sm: 10px;
            --transition: 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1045;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border);
            box-shadow: 4px 0 24px rgba(74, 144, 226, 0.06);
            transition: transform var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #34D399, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            flex-shrink: 0;
        }

        .brand-text h6 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
        }

        .brand-text small {
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
            flex: 1;
        }

        .nav-section {
            padding: 0.75rem 0.5rem 0.4rem;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.7;
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.6rem 0.875rem;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: var(--radius-sm);
            margin-bottom: 2px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.12), rgba(5, 150, 105, 0.08));
            opacity: 0;
            border-radius: var(--radius-sm);
            transition: opacity var(--transition);
        }

        .sidebar .nav-link:hover {
            color: var(--text-primary);
        }

        .sidebar .nav-link:hover::before {
            opacity: 1;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #34D399, #059669);
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
        }

        .sidebar .nav-link.active::before {
            opacity: 0;
        }

        .sidebar .nav-link i {
            font-size: 15px;
            width: 18px;
            flex-shrink: 0;
            transition: transform var(--transition);
        }

        .sidebar .nav-link:hover i {
            transform: translateX(2px);
        }

        .sidebar .nav-link.active i {
            transform: none;
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 0.6rem 0.875rem;
            background: rgba(239, 68, 68, 0.06);
            border: 1px solid rgba(239, 68, 68, 0.12);
            border-radius: var(--radius-sm);
            color: var(--danger);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.12);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(31, 41, 55, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            transition: opacity var(--transition);
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        .topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0.875rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            gap: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 15px;
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
        }

        .topbar-icon-btn:hover {
            background: white;
            color: var(--text-primary);
            box-shadow: var(--shadow-sm);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--danger);
            border: 1.5px solid white;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px 4px 4px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 99px;
            cursor: pointer;
            transition: all var(--transition);
        }

        .user-pill:hover {
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #34D399, #059669);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-toggle-sidebar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 18px;
            display: none !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
        }

        .btn-toggle-sidebar:hover {
            background: white;
            color: var(--text-primary);
        }

        .page-content {
            padding: 1.75rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.375rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-hero {
            background: linear-gradient(135deg, #34D399 0%, #059669 50%, #047857 100%);
            border-radius: var(--radius);
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .page-hero::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .anim-fade-up {
            animation: fadeInUp 0.4s ease both;
        }

        .anim-slide-left {
            animation: slideInLeft 0.35s ease both;
        }

        .anim-scale {
            animation: scaleIn 0.3s ease both;
        }

        .delay-1 {
            animation-delay: 0.05s;
        }

        .delay-2 {
            animation-delay: 0.10s;
        }

        .delay-3 {
            animation-delay: 0.15s;
        }

        .delay-4 {
            animation-delay: 0.20s;
        }

        .delay-5 {
            animation-delay: 0.25s;
        }

        .delay-6 {
            animation-delay: 0.30s;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.12);
            color: #15803D;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.12);
            color: #B45309;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #B91C1C;
        }

        .badge-info {
            background: rgba(110, 168, 254, 0.15);
            color: #1D4ED8;
        }

        .badge-gray {
            background: rgba(107, 114, 128, 0.10);
            color: #4B5563;
        }

        .table thead tr th {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            background: rgba(240, 244, 255, 0.6);
        }

        .table tbody tr td {
            font-size: 13.5px;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(240, 244, 255, 0.8);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover td {
            background: rgba(52, 211, 153, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #34D399, #059669);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.45);
            transform: translateY(-1px);
            color: white;
        }

        .btn-outline-primary {
            border-color: rgba(52, 211, 153, 0.4);
            color: #059669;
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #34D399, #059669);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .btn-outline-secondary {
            border-color: var(--border);
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-outline-secondary:hover {
            background: var(--bg);
            color: var(--text-primary);
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text-primary);
            background: var(--surface);
            transition: all var(--transition);
            padding: 0.5rem 0.875rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #34D399;
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2);
            outline: none;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .alert {
            border-radius: var(--radius-sm);
            border: none;
            font-size: 13.5px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.10);
            color: #15803D;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.10);
            color: #B91C1C;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.10);
            color: #B45309;
        }

        .alert-info {
            background: rgba(110, 168, 254, 0.12);
            color: #1D4ED8;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(52, 211, 153, 0.3);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(52, 211, 153, 0.5);
        }

        @media (max-width:991.98px) {
            .sidebar {
                transform: translateX(-100%) !important;
                animation: none !important;
            }

            .sidebar.show {
                transform: translateX(0) !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .btn-toggle-sidebar {
                display: flex !important;
            }

            .page-content {
                padding: 1rem;
            }

            .topbar {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width:575.98px) {
            .user-name {
                display: none !important;
            }

            .topbar-title {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon"><i class="bi bi-people-fill"></i></div>
                <div class="brand-text">
                    <h6>Tayabas ICT Hub</h6>
                    <small>Human Resources</small>
                </div>
            </div>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('hr.dashboard') }}"
                class="nav-link {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i>
                Dashboard</a>
            <div class="nav-section mt-2">Employee</div>
            <a href="{{ route('hr.employees.index') }}"
                class="nav-link {{ request()->routeIs('hr.employees.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Employees
            </a>
            <div class="nav-section mt-2">Leave</div>
            <a href="{{ route('hr.leave.index') }}"
                class="nav-link {{ request()->routeIs('hr.leave.*') ? 'active' : '' }}"><i
                    class="bi bi-calendar-check"></i> Leave Requests</a>
            <div class="nav-section mt-2">Certificates</div>
            <a href="{{ route('hr.certificates.index') }}"
                class="nav-link {{ request()->routeIs('hr.certificates.*') ? 'active' : '' }}"><i
                    class="bi bi-file-earmark-text"></i> Certificate Requests</a>
            <div class="nav-section mt-2">Other</div>
            <a href="{{ route('hr.attendance.index') }}"
                class="nav-link {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Attendance
            </a>
            <a href="{{ route('hr.board.index') }}"
                class="nav-link {{ request()->routeIs('hr.board.*') ? 'active' : '' }}"><i class="bi bi-megaphone"></i>
                Notice Board</a>
            <a href="{{ route('hr.messages.index') }}"
                class="nav-link {{ request()->routeIs('hr.messages.*') ? 'active' : '' }}"><i
                    class="bi bi-chat-dots"></i> Messages</a>
            <a href="{{ route('hr.profile.show') }}"
                class="nav-link {{ request()->routeIs('hr.profile.*') ? 'active' : '' }}"><i
                    class="bi bi-person-circle"></i> My Profile</a>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i
                        class="bi bi-box-arrow-left"></i><span>Logout</span></button>
            </form>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <button class="btn-toggle-sidebar" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <h6 class="topbar-title">@yield('page-title', 'Dashboard')</h6>
            </div>
            <div class="topbar-right">
                <div class="topbar-icon-btn"><i class="bi bi-bell"></i><span class="notif-dot"></span></div>
                <div class="user-pill">
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->username, 0, 2)) }}</div>
                    <span class="user-name">{{ Auth::user()->username }}</span>
                </div>
            </div>
        </div>
        <div class="page-content">@yield('content')</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar'),
            overlay = document.getElementById('sidebarOverlay'),
            toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        toggleBtn?.addEventListener('click', () => sidebar.classList.contains('show') ? closeSidebar() : openSidebar());
        overlay.addEventListener('click', closeSidebar);
        sidebar.querySelectorAll('.nav-link').forEach(l => l.addEventListener('click', () => {
            if (window.innerWidth < 992) closeSidebar();
        }));
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) closeSidebar();
        });
    </script>
    @stack('scripts')
</body>

</html>
