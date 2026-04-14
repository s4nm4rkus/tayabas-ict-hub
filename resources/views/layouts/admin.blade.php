<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Tayabas ICT Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --sidebar-bg: #1a1f2e;
            --sidebar-hover: #2d3447;
            --sidebar-accent: #4f8ef7;
            --sidebar-text: #8892a4;
        }

        body {
            background: #f4f6f9;
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
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid #2d3447;
            flex-shrink: 0;
        }

        .sidebar-brand h6 {
            color: #fff;
            font-weight: 600;
            margin: 0;
            font-size: 16px;
        }

        .sidebar-brand small {
            color: var(--sidebar-text);
            font-size: 12px;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section {
            padding: 0.5rem 1.5rem 0.25rem;
            font-size: 11px;
            font-weight: 600;
            color: var(--sidebar-text);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.6rem 1.5rem;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-accent);
        }

        .sidebar .nav-link i {
            font-size: 16px;
            width: 18px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #2d3447;
            flex-shrink: 0;
        }

        /* ── Overlay (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            gap: 1rem;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 600;
            color: #1a1f2e;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        /* Hamburger button */
        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: #495057;
            font-size: 20px;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 6px;
            display: none;
            /* hidden on desktop */
            line-height: 1;
            transition: background 0.2s;
        }

        .btn-toggle-sidebar:hover {
            background: #f0f0f0;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #495057;
        }

        .user-badge .username-label {
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--sidebar-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
        }

        /* ── Page Content ── */
        .page-content {
            padding: 1.5rem;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1a1f2e;
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: var(--sidebar-text);
        }

        /* ── Responsive: Tablet & Mobile ── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .btn-toggle-sidebar {
                display: flex;
                align-items: center;
            }

            .page-content {
                padding: 1rem;
            }

            .topbar {
                padding: 0.65rem 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .user-badge .username-label {
                display: none;
                /* hide email text, keep avatar */
            }

            .topbar-title {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    {{-- ── Sidebar Overlay (mobile tap-to-close) ── --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ── Sidebar ── --}}
    <div class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <h6><i class="bi bi-shield-check me-2"></i>Tayabas ICT Hub</h6>
            <small>Super Administrator</small>
        </div>

        <div class="sidebar-nav">

            <div class="nav-section">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>

            <div class="nav-section mt-2">Employee</div>
            <a href="{{ route('admin.employees.index') }}"
                class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Employees
            </a>
            <a href="{{ route('admin.employees.create') }}"
                class="nav-link {{ request()->routeIs('admin.employees.create') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> Add Employee
            </a>

            <div class="nav-section mt-2">Management</div>
            <a href="{{ route('admin.profile.show') }}"
                class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> My Profile
            </a>
            <a href="{{ route('admin.leaves.index') }}"
                class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Leave Requests
            </a>
            <a href="{{ route('admin.attendance.index') }}"
                class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Attendance
            </a>
            <a href="#" class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Certificates
            </a>
            <a href="{{ route('admin.messages.index') }}"
                class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i> Messages
            </a>
            <a href="{{ route('admin.board.index') }}"
                class="nav-link {{ request()->routeIs('admin.board.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Notice Board
            </a>

            <div class="nav-section mt-2">System</div>
            <a href="{{ route('admin.roles.index') }}"
                class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Roles
            </a>
            <a href="{{ route('admin.salary.index') }}"
                class="nav-link {{ request()->routeIs('admin.salary.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Salary Grade
            </a>
            <a href="{{ route('admin.audit.index') }}"
                class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Audit Trail
            </a>
            <a href="{{ route('admin.backup.index') }}"
                class="nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                <i class="bi bi-database"></i> Backup
            </a>

        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100"
                    style="background:#2d3447; color:#8892a4; border:none; text-align:left; padding:0.6rem 0.75rem; border-radius:6px;">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>

    {{-- ── Main Content ── --}}
    <div class="main-content" id="mainContent">

        <div class="topbar">
            <div class="topbar-left">
                {{-- Hamburger: only visible on mobile/tablet --}}
                <button class="btn-toggle-sidebar" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="topbar-title">@yield('page-title', 'Dashboard')</h6>
            </div>

            <div class="topbar-right">
                <div class="user-badge">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <span class="username-label">{{ Auth::user()->username }}</span>
                </div>
            </div>
        </div>

        <div class="page-content">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // prevent background scroll
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

        // Tap overlay to close
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on nav link click (mobile UX)
        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        // On resize to desktop: reset sidebar and overlay state
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
