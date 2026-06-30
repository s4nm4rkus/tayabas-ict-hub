<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SDO Tayabas Portal</title>
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
            --sidebar-bg: #151f2c;
            --sidebar-text: #E5E7EB;
            --sidebar-text-secondary: #9CA3AF;
            --sidebar-accent: #A78BFA;
            --bg: #F8FAFC;
            --surface: #FFFFFF;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --border: rgba(168, 85, 247, 0.1);
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-width: 260px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.08);
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

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1045;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(168, 85, 247, 0.15);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            transition: transform var(--transition);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(168, 85, 247, 0.15);
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
            background: linear-gradient(135deg, #A78BFA, #8B5CF6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.4);
            flex-shrink: 0;
        }

        .brand-text h6 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            line-height: 1.3;
            letter-spacing: 0.02em;
        }

        .brand-text small {
            font-size: 10.5px;
            color: var(--sidebar-text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-nav {
            padding: 0.5rem 0.75rem;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .nav-group {
            flex-shrink: 0;
        }

        .nav-section {
            padding: 0.75rem 0.875rem 0.5rem;
            font-size: 9.5px;
            font-weight: 700;
            color: var(--sidebar-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            opacity: 0.6;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text-secondary);
            padding: 0.65rem 0.875rem;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 11px;
            border-radius: var(--radius-sm);
            margin-bottom: 3px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--sidebar-accent);
            transform: scaleY(0);
            transform-origin: top;
            transition: transform var(--transition);
            border-radius: 0 4px 4px 0;
        }

        .sidebar .nav-link:hover {
            color: var(--sidebar-text);
            background: rgba(168, 85, 247, 0.12);
        }

        .sidebar .nav-link.active {
            color: var(--sidebar-accent);
            background: rgba(168, 85, 247, 0.15);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link i {
            font-size: 14px;
            width: 16px;
            flex-shrink: 0;
            transition: transform var(--transition);
        }

        .sidebar .nav-link:hover i {
            transform: translateX(2px);
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(168, 85, 247, 0.15);
            flex-shrink: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 0.65rem 0.875rem;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: var(--radius-sm);
            color: #FCA5A5;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.18);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(11, 31, 58, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            transition: opacity var(--transition);
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ── Main ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        /* ── Topbar ── */
        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(168, 85, 247, 0.1);
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
            border: 1px solid rgba(168, 85, 247, 0.1);
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
            border: 1px solid rgba(168, 85, 247, 0.1);
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
            background: linear-gradient(135deg, #A78BFA, #8B5CF6);
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
            border: 1px solid rgba(168, 85, 247, 0.1);
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

        /* ── Page Content ── */
        .page-content {
            padding: 1.75rem;
        }

        /* ── Stat Cards ── */
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

        /* ── Gradient Hero ── */
        .page-hero {
            background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 50%, #7C3AED 100%);
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

        /* ── Animations ── */
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

        /* ── Badges ── */
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
            background: rgba(168, 85, 247, 0.15);
            color: #8B5CF6;
        }

        .badge-gray {
            background: rgba(107, 114, 128, 0.10);
            color: #4B5563;
        }

        /* ── Tables ── */
        .table thead tr th {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            background: rgba(168, 85, 247, 0.04);
        }

        .table tbody tr td {
            font-size: 13.5px;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(168, 85, 247, 0.08);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover td {
            background: rgba(168, 85, 247, 0.04);
        }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #A78BFA, #8B5CF6);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.45);
            transform: translateY(-1px);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-primary {
            border-color: rgba(168, 85, 247, 0.4);
            color: #8B5CF6;
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #A78BFA, #8B5CF6);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
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

        /* ── Form Controls ── */
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
            border-color: #A78BFA;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
            outline: none;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        /* ── Alert ── */
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
            background: rgba(168, 85, 247, 0.12);
            color: #8B5CF6;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(168, 85, 247, 0.3);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(168, 85, 247, 0.5);
        }

        /* ── Responsive ── */
        @media (max-width: 991.98px) {
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

        @media (max-width: 575.98px) {
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
                <div class="brand-icon"><i class="bi bi-person-circle"></i></div>
                <div class="brand-text">
                    <h6>SDO Tayabas Portal</h6>
                    <small>{{ Auth::user()->user_pos ?? 'Employee' }}</small>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            {{-- Main Section --}}
            <div class="nav-group">
                <div class="nav-section">Main</div>
                <a href="{{ route('employee.dashboard') }}"
                    class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                <a href="{{ route('employee.profile.show') }}"
                    class="nav-link {{ request()->routeIs('employee.profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> My Profile
                </a>
            </div>

            {{-- Requests Section --}}
            <div class="nav-group" style="margin-top:0.5rem;">
                <div class="nav-section">Requests</div>
                <a href="{{ route('employee.leave.index') }}"
                    class="nav-link {{ request()->routeIs('employee.leave.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Leave
                </a>
                <a href="{{ route('employee.certificates.index') }}"
                    class="nav-link {{ request()->routeIs('employee.certificates.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Certificates
                </a>
            </div>

            {{-- Tools Section --}}
            <div class="nav-group" style="margin-top:0.5rem;">
                <div class="nav-section">Tools</div>
                <a href="{{ route('employee.attendance.index') }}"
                    class="nav-link {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Attendance
                </a>
                <a href="{{ route('employee.board.index') }}"
                    class="nav-link {{ request()->routeIs('employee.board.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i> Notice Board
                </a>
                <a href="{{ route('employee.messages.index') }}"
                    class="nav-link {{ request()->routeIs('employee.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i> Messages
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
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
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

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

        toggleBtn?.addEventListener('click', () =>
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar()
        );
        overlay.addEventListener('click', closeSidebar);

        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) closeSidebar();
        });
    </script>
    @stack('scripts')
</body>

</html>
