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
            --sidebar-width: 250px;
            --sidebar-bg: #4a2c00;
            --sidebar-hover: #6b3f00;
            --sidebar-accent: #f0ad4e;
            --sidebar-text: #d4a96a;
        }

        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }

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
            border-bottom: 1px solid #6b3f00;
            flex-shrink: 0;
        }

        .sidebar-brand h6 {
            color: #fff;
            font-weight: 600;
            margin: 0;
            font-size: 15px;
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
            border-top: 1px solid #6b3f00;
            flex-shrink: 0;
        }

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

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

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

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1f2e;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: #495057;
            font-size: 20px;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 6px;
            display: none;
            line-height: 1;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #495057;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0ad4e;
            color: #4a2c00;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .page-content {
            padding: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

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
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h6><i class="bi bi-person-workspace me-2"></i>Tayabas ICT Hub</h6>
            <small>Department Head</small>
        </div>

        <div class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('head.dashboard') }}"
                class="nav-link {{ request()->routeIs('head.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>

            <div class="nav-section mt-2">Leave</div>
            <a href="{{ route('head.leave.index') }}"
                class="nav-link {{ request()->routeIs('head.leave.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Leave Requests
            </a>
            <div class="nav-section mt-2">Other</div>
            <a href="#" class="nav-link {{ request()->routeIs('head.attendance.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Attendance
            </a>
            <a href="{{ route('head.board.index') }}"
                class="nav-link {{ request()->routeIs('head.board.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone"></i> Notice Board
            </a>
            <a href="{{ route('head.messages.index') }}"
                class="nav-link {{ request()->routeIs('head.messages.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i> Messages
            </a>
            <a href="#" class="nav-link {{ request()->routeIs('head.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> My Profile
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100"
                    style="background:#6b3f00;color:#d4a96a;border:none;
                       text-align:left;padding:0.6rem 0.75rem;border-radius:6px;">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn-toggle-sidebar" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="topbar-title">@yield('page-title', 'Dashboard')</h6>
            </div>
            <div class="topbar-right">
                <div class="user-badge">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <span>{{ Auth::user()->username }}</span>
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
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        toggleBtn.addEventListener('click', () => sidebar.classList.contains('show') ? closeSidebar() : openSidebar());
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
